<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\CreatePurchaseOrderRequest;
use App\Http\Resources\BaseResource;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Services\ApproveService;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = PurchaseOrder::filter($request->all())
            ->where('company_id', $user->company_id)
            ->with(['approveInstance', 'currentApproveItemInstance'])
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        // $this->authorize('own', $purchaseOrder);

        return $this->success(new BaseResource($purchaseOrder->load(['purchaseOrderItems.goods', 'approveInstance', 'currentApproveItemInstance'])));
    }


    public function store(CreatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        $user = auth('client')->user();
        $params = $request->all();

        try {
            $purchaseOrder->fill(array_merge($params, ['company_id' => $user->company_id]));
            $purchaseOrder->save();

            if (isset($params['purchase_order_items']) && count($params['purchase_order_items'])) {
                foreach ($params['purchase_order_items'] as $item) {
                    PurchaseOrderItem::query()->create(array_merge($item, ['purchase_order_id' => $purchaseOrder->id]));
                }
            }

            // 自定义审批
            if (isset($params['approve_id']) && $params['approve_id']) {
                // 生成 instance
                app(ApproveService::class)->generateInstances($params['approve_id'], $purchaseOrder);

                // 执行审批流程
                app(ApproveService::class)->approveBegin($purchaseOrder);
            }
        } catch (\Exception $exception) {
            return $this->failed($exception->getMessage());
        }


        return $this->message('操作成功');
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        // $this->authorize('own', $purchaseOrder);

        $params = $request->all();

        $purchaseOrder->fill($params);
        $purchaseOrder->save();

        if (isset($params['purchase_order_items']) && count($params['purchase_order_items'])) {
            foreach ($params['purchase_order_items'] as $item) {
                $purchaseOrderItem = new PurchaseOrderItem(['purchase_order_id' => $purchaseOrder->id]);
                if (isset($item['id']) && $item['id']) {
                    $purchaseOrderItem = PurchaseOrderItem::query()->where('id', $item['id'])->first();
                }
                $purchaseOrderItem->fill($item);
                $purchaseOrderItem->save();
            }
        }

        return $this->message('操作成功');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        // $this->authorize('own', $purchaseOrder);

        $purchaseOrder->delete();

        return $this->message('操作成功');
    }
}
