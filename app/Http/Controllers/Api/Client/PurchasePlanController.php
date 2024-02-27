<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\CreatePurchasePlanRequest;
use App\Http\Resources\BaseResource;
use App\Models\PurchasePlan;
use App\Models\PurchasePlanItem;
use App\Services\ApproveService;
use Illuminate\Http\Request;

class PurchasePlanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();
        $list = PurchasePlan::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(PurchasePlan $purchasePlan)
    {
        // $this->authorize('own', $purchasePlan);

        return $this->success(new BaseResource($purchasePlan->load(['purchasePlanItems.goods'])));
    }


    public function store(CreatePurchasePlanRequest $request, PurchasePlan $purchasePlan)
    {
        $user = auth('client')->user();
        $params = $request->all();

        try {

            $purchasePlan->fill(array_merge($params, ['company_id' => $user->company_id]));
            $purchasePlan->save();

            if (isset($params['purchase_plan_items']) && count($params['purchase_plan_items'])) {
                foreach ($params['purchase_plan_items'] as $item) {
                    PurchasePlanItem::query()->create(array_merge($item, ['purchase_plan_id' => $purchasePlan->id]));
                }
            }

            // 自定义审批
            if (isset($params['approve_id']) && $params['approve_id']) {
                // 生成 instance
                app(ApproveService::class)->generateInstances($params['approve_id'], $purchasePlan);

                // 执行审批流程
                app(ApproveService::class)->approveBegin($purchasePlan);
            }
        } catch (\Exception $exception) {
            return $this->failed($exception->getMessage());
        }


        return $this->message('操作成功');
    }

    public function update(Request $request, PurchasePlan $purchasePlan)
    {
        // $this->authorize('own', $purchasePlan);

        $params = $request->all();

        $purchasePlan->fill($params);
        $purchasePlan->save();

        if (isset($params['purchase_plan_items']) && count($params['purchase_plan_items'])) {
            foreach ($params['purchase_plan_items'] as $item) {
                $purchasePlanItem = new PurchasePlanItem(['purchase_plan_id' => $purchasePlan->id]);
                if (isset($item['id']) && $item['id']) {
                    $purchasePlanItem = PurchasePlanItem::query()->where('id', $item['id'])->first();
                }
                $purchasePlanItem->fill($item);
                $purchasePlanItem->save();
            }
        }

        return $this->message('操作成功');
    }

    public function destroy(PurchasePlan $purchasePlan)
    {
        // $this->authorize('own', $purchasePlan);

        $purchasePlan->delete();

        return $this->message('操作成功');
    }
}
