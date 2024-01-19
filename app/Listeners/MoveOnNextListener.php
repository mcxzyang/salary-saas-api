<?php

namespace App\Listeners;

use App\Events\ApproveInstanceFinishedEvent;
use App\Models\CompanyOption;
use App\Models\CompanyOptionSet;
use App\Models\Order;
use App\Models\Workorder;
use App\Services\OrderService;

class MoveOnNextListener
{
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ApproveInstanceFinishedEvent  $event
     *
     * @return void
     * @throws \App\Exceptions\InvalidRequestException
     */
    public function handle(ApproveInstanceFinishedEvent $event): void
    {
        $model = $event->model;
        $approveInstance = $event->approveInstance;

        switch ($approveInstance->modelable_type) {
            case Order::class:
                // 从草稿变成正常
                $order = Order::query()->find($approveInstance->modelable_id);

                $order->status = 1;
                $order->save();

                // 发起自定义状态流转
                app(OrderService::class)->stateFactoryBegin($order);

                // 自动转工单
                $companyOptionSet = CompanyOptionSet::query()->where(['company_id' => $order->company_id, 'company_option_code' => CompanyOption::CODE_ORDER_TO_WORKORDER])->first();
                if ($companyOptionSet && $companyOptionSet->value === '1') {
                    $orderItems = $order->orderItems;
                    if ($orderItems && count($orderItems)) {
                        foreach ($orderItems as $orderItem) {
                            Workorder::query()->create([
                                'company_id' => $order->company_id,
                                'goods_id' => $orderItem->goods_id,
                                'planned_number' => $orderItem->number,
                            ]);
                        }
                    }
                }
                break;
        }
    }
}
