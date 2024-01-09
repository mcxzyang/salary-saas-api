<?php

namespace App\Http\Controllers\Api\Client;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Goods;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StateFactoryItemPersonInstance;
use App\Services\StateFactoryService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Order::filter($request->all())
            ->with(['currentStateFactoryItemInstance'])
            ->where('is_deleted', 0)
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Order $order)
    {
        $this->authorize('own', $order);

        return $this->success(new BaseResource($order->load(['customer', 'stateFactoryInstance.stateFactoryItemInstances', 'currentStateFactoryItemInstance'])));
    }

    public function store(Request $request, Order $order)
    {
        $this->validate($request, [
            'state_factory_id' => 'required',
            'customer_id' => 'required',
            'order_items' => 'required|array',
            'order_items.*.goods_id' => 'required',
            'order_items.*.number' => 'integer'
        ]);

        $user = auth('client')->user();

        $params = $request->all();

        try {
            \DB::transaction(function () use ($order, $params, $user, $request) {
                $order->fill(array_merge($params, ['company_id' => $user->company_id, 'company_user_id' => $user->id]));
                $order->save();

                $orderTotal = 0;
                if (isset($params['order_items']) && count($params['order_items'])) {
                    foreach ($params['order_items'] as $orderItemParam) {
                        $goods = Goods::query()->where(['company_id' => $user->company_id, 'id' => $orderItemParam['goods_id']])->first();
                        if (!$goods) {
                            throw new InvalidRequestException('产品不存在');
                        }
                        $total = $goods->price * $orderItemParam['number'];

                        OrderItem::query()->create([
                            'order_id' => $order->id,
                            'goods_id' => $goods->id,
                            'number' => $orderItemParam['number'],
                            'price' => $goods->price,
                            'total' => $total,
                            'remark' => $orderItemParam['remark'] ?? null
                        ]);

                        $orderTotal += $total;
                    }
                }
                $order->total = $orderTotal;
                $order->save();

                // 生成 instance
                app(StateFactoryService::class)->generateInstances($params['state_factory_id'], 'orders', $order->id);

                // 开始第一步
                $currentStateFactoryItemInstance = app(StateFactoryService::class)->nextStep('orders', $order->id);
                if ($currentStateFactoryItemInstance && (!$order->state_factory_instance_id || !$order->current_state_factory_item_instance_id)) {
                    $order->state_factory_instance_id = $currentStateFactoryItemInstance->state_factory_instance_id;
                    $order->current_state_factory_item_instance_id = $currentStateFactoryItemInstance->id;
                    $order->save();
                }
            });
        } catch (\Exception $exception) {
            return $this->failed($exception->getMessage());
        }

        return $this->message('操作成功');
    }

    public function nextStep(Order $order)
    {
        $this->authorize('own', $order);

        $stateFactoryInstance = $order->stateFactoryInstance;
        if ($stateFactoryInstance->status === 2) {
            return $this->failed('该实例已完成');
        }
        try {
            \DB::transaction(function () use ($order) {
                $currentStateFactoryItemInstance = app(StateFactoryService::class)->nextStep('orders', $order->id);
                if ($currentStateFactoryItemInstance) {
                    $order->current_state_factory_item_instance_id = $currentStateFactoryItemInstance->id;
                    $order->save();
                }
            });
        } catch (\Exception $e) {
            return $this->failed($e->getMessage());
        }

        return $this->message('操作成功');
    }
}