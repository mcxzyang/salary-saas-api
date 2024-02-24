<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\CreatePurchaseOrderRequest;
use App\Http\Resources\BaseResource;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = PurchaseOrder::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        // $this->authorize('own', $purchaseOrder);

        return $this->success(new BaseResource($purchaseOrder->load(['purchaseOrderItem'])));
    }


    public function store(CreatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        $user = auth('client')->user();
        $params = $request->all();

        $purchaseOrder->fill(array_merge($params, ['company_id' => $user->company_id]));
        $purchaseOrder->save();

        if (isset($params['purchase_order_items']) && count($params['purchase_order_items'])) {
            foreach ($params['purchase_order_items'] as $item) {
                PurchaseOrderItem::query()->create(array_merge($item, ['purchase_order_id' => $purchaseOrder->id]));
            }
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
