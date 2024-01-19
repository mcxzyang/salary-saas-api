<?php

namespace App\Services;

use App\Events\ApproveInstanceFinishedEvent;
use App\Exceptions\InvalidRequestException;
use App\Models\Approve;
use App\Models\ApproveInstance;
use App\Models\ApproveItemInstance;
use App\Models\ApproveItemPersonInstance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ApproveService
{
    public function generateInstances($approveId, Model $model)
    {
        $modelType = get_class($model);
        $modelId = $model->id;

        $approve = Approve::query()->where('id', $approveId)->first();
        if (!$approve) {
            throw new InvalidRequestException('自定义审批不存在');
        }
        $approveInstance = ApproveInstance::query()->create([
            'company_id' => $approve->company_id,
            'approve_id' => $approve->id,
            'modelable_type' => $modelType,
            'modelable_id' => $modelId,
            'type' => $approve->type
        ]);
        $approveItems = $approve->approveItems;
        if ($approveItems && count($approveItems)) {
            foreach ($approveItems as $approveItem) {
                $approveItemInstance = ApproveItemInstance::query()->create([
                    'approve_id' => $approve->id,
                    'approve_item_id' => $approveItem->id,
                    'approve_instance_id' => $approveInstance->id,
                    'sort' => $approveItem->sort,
                    'condition_type' => $approveItem->condition_type,
                    'status' => 0
                ]);

                $approveItemPersons = $approveItem->approveItemPersons;
                if ($approveItemPersons && count($approveItemPersons)) {
                    foreach ($approveItemPersons as $approveItemPerson) {
                        ApproveItemPersonInstance::query()->create([
                            'approve_item_id' => $approveItem->id,
                            'approve_item_person_id' => $approveItemPerson->id,
                            'approve_item_instance_id' => $approveItemInstance->id,
                            'company_id' => $approve->company_id,
                            'company_user_id' => $approveItemPerson->company_user_id,
                        ]);
                    }
                }
            }
        }
    }

    public function nextStep(Model $model)
    {
        $modelType = get_class($model);
        $modelId = $model->id;

        $approveInstance = ApproveInstance::query()->where(['modelable_type' => $modelType, 'modelable_id' => $modelId])->first();
        if (!$approveInstance) {
            throw new InvalidRequestException('自定义审批不存在');
        }
        if ($approveInstance->status !== 1) {
            throw new InvalidRequestException('自定义流程实例状态错误或已完成');
        }

        $waitingCount = ApproveItemInstance::query()->where(['approve_instance_id' => $approveInstance->id, 'status' => 0])->count();
        $inProgressCount = ApproveItemInstance::query()->where(['approve_instance_id' => $approveInstance->id, 'status' => 1])->count();

        // 找到最近一条还没开始的 instance
        $currentApproveItemInstance = ApproveItemInstance::query()->where(['approve_instance_id' => $approveInstance->id, 'status' => 0])->orderBy('sort')->first();
        if ($currentApproveItemInstance) {
            // 首次分配
            if ($waitingCount > 0 && $inProgressCount <= 0) {
                $currentApproveItemInstance->status = 1;
                $currentApproveItemInstance->save();

                return $currentApproveItemInstance;
            }
//            throw new InvalidRequestException('该实例已运行完成');
        }


        // 找到最近的一条正在进行的 instance
        $currentApproveItemInstance = ApproveItemInstance::query()->where(['approve_instance_id' => $approveInstance->id, 'status' => 1])->orderBy('sort')->first();
        if (!$currentApproveItemInstance) {
            return true;
//            throw new InvalidRequestException('该实例已运行完成');
        }

        // 有审核的完成条件
        if ($currentApproveItemInstance->condition_type) {
            // 审核人
            $approveItemPersonInstances = ApproveItemPersonInstance::query()->where(['approve_item_instance_id' => $currentApproveItemInstance->id])->get();
            if ($approveItemPersonInstances && count($approveItemPersonInstances)) {
                $approveCount = ApproveItemPersonInstance::query()->where(['approve_item_instance_id' => $currentApproveItemInstance->id, 'result' => 1])->count();

                switch ($currentApproveItemInstance->condition_type) {
                    case 1: // 需要全部完成
                        if ($approveCount !== count($approveItemPersonInstances)) {
                            return true;
//                            throw new InvalidRequestException('该审批流程还有审核人未通过');
                        }
                        break;
                    case 2: // 任一完成
                        if ($approveCount === 0) {
                            return true;
//                            throw new InvalidRequestException('该审批流程还未通过任一审核人的审批');
                        }
                        break;
                }
            }
        }
        // 将该条实例置为 已完成
        $currentApproveItemInstance->status = 2;
        $currentApproveItemInstance->save();

        $next = ApproveItemInstance::query()->where(['approve_instance_id' => $approveInstance->id, 'status' => 0])->orderBy('sort')->first();
        if (!$next) {
            // 将整个实例置为 已完成
            $approveInstance->status = 2;
            $approveInstance->save();

            event(new ApproveInstanceFinishedEvent($model, $approveInstance));
        } else {
            // 下一个实例置为 开始
            $next->status = 1;
            $next->save();
        }

        return $next;
    }
}
