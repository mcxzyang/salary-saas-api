<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    /**
     * 执行自定义状态流程并回写
     *
     * @throws \App\Exceptions\InvalidRequestException
     */
    public function stateFactoryBegin(Order $order)
    {
        $currentStateFactoryItemInstance = app(StateFactoryService::class)->nextStep($order);
        if ($currentStateFactoryItemInstance && (!$order->state_factory_instance_id || !$order->current_state_factory_item_instance_id)) {
            $order->state_factory_instance_id = $currentStateFactoryItemInstance->state_factory_instance_id;
            $order->current_state_factory_item_instance_id = $currentStateFactoryItemInstance->id;
            $order->save();
        }
        return $order;
    }

    /**
     * 执行审批流程并回写
     *
     * @throws \App\Exceptions\InvalidRequestException
     */
    public function approveBegin(Order $order)
    {
        $currentApproveItemInstance = app(ApproveService::class)->nextStep($order);
        if ($currentApproveItemInstance && (!$order->approve_instance_id || !$order->current_approve_item_instance_id)) {
            $order->approve_instance_id = $currentApproveItemInstance->approve_instance_id;
            $order->current_approve_item_instance_id = $currentApproveItemInstance->id;
            $order->save();
        }
    }
}
