<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    /**
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
}
