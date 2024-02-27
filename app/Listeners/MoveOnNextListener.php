<?php

namespace App\Listeners;

use App\Events\ApproveInstanceFinishedEvent;
use App\Models\CompanyOption;
use App\Models\CompanyOptionSet;
use App\Models\Order;
use App\Models\PurchaseOrder;
use App\Models\PurchasePlan;
use App\Models\Workorder;
use App\Models\WorkorderTask;
use App\Models\WorkorderTaskUser;
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

        \Log::info('已进入');

        switch ($approveInstance->modelable_type) {
            case Order::class:
                // 从草稿变成正常
                $order = Order::query()->find($approveInstance->modelable_id);

                $order->status = 1;
                $order->save();

                // 发起自定义状态流转
                app(OrderService::class)->stateFactoryBegin($order);

                // 自动转工单
//                $companyOptionSet = CompanyOptionSet::query()->where(['company_id' => $order->company_id, 'company_option_code' => CompanyOption::CODE_ORDER_TO_WORKORDER])->first();

                if ($approveInstance && $approveInstance->if_auto_next) {
                    $orderItems = $order->orderItems;
                    if ($orderItems && count($orderItems)) {
                        foreach ($orderItems as $orderItem) {
                            $workingTechnologyItems = $orderItem->goods->workingTechnology->workingTechnologyItems;

                            $workorder = Workorder::query()->create([
                                'company_id' => $order->company_id,
                                'order_id' => $order->id,
                                'goods_id' => $orderItem->goods_id,
                                'planned_number' => $orderItem->number,
                            ]);

                            if ($workingTechnologyItems && count($workingTechnologyItems)) {
                                foreach ($workingTechnologyItems as $key => $workingTechnologyItem) {
                                    $status = 1;
                                    if ($workorder->report_type === 2) { // 顺序型
                                        $status = $key === 0 ? 1 : 0;
                                    }
                                    $workingProcess = $workingTechnologyItem->workingProcess;
                                    if ($workingProcess) {
                                        $workorderTask = WorkorderTask::query()->create([
                                            'workorder_id' => $workorder->id,
                                            'name' => $workingProcess->name,
                                            'no' => $workingProcess->no,
                                            'working_process_id' => $workingProcess->id,
                                            'report_working_rate' => $workingProcess->report_working_rate,
                                            'report_working_permission' => $workingProcess->report_working_permission,
                                            'plan_number' => $workorder->planned_number,
                                            'sort' => $workingTechnologyItem->sort,
                                            'status' => $status
                                        ]);

                                        $permissions = $workingProcess->report_working_permission;
                                        if ($permissions && count($permissions)) {
                                            foreach ($permissions as $companyUserId) {
                                                WorkorderTaskUser::query()->create([
                                                    'workorder_task_id' => $workorderTask->id,
                                                    'company_user_id' => $companyUserId
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                break;
            case PurchaseOrder::class:
                $purchaseOrder = PurchaseOrder::query()->find($approveInstance->modelable_id);

                $purchaseOrder->status = 1;
                $purchaseOrder->save();
                break;
            case PurchasePlan::class:
                $purchasePlan = PurchasePlan::query()->find($approveInstance->modelable_id);

                $purchasePlan->status = 1;
                $purchasePlan->save();
                break;
        }
    }
}
