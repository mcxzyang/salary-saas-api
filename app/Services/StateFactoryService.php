<?php

namespace App\Services;

use App\Exceptions\InvalidRequestException;
use App\Models\StateFactory;
use App\Models\StateFactoryInstance;
use App\Models\StateFactoryItemInstance;
use App\Models\StateFactoryItemPersonInstance;
use Illuminate\Database\Eloquent\Model;

class StateFactoryService
{
    public function generateInstances($stateFactoryId, Model $model)
    {
        $modelType = get_class($model);
        $modelId = $model->id;

        $stateFactory = StateFactory::query()->where('id', $stateFactoryId)->first();
        if (!$stateFactory) {
            throw new InvalidRequestException('自定义流程不存在');
        }
        $stateFactoryInstance = StateFactoryInstance::query()->create([
            'company_id' => $stateFactory->company_id,
            'state_factory_id' => $stateFactory->id,
            'modelable_type' => $modelType,
            'modelable_id' => $modelId
        ]);

        $stateFactoryItems = $stateFactory->stateFactoryItems;
        if ($stateFactoryItems && count($stateFactoryItems)) {
            foreach ($stateFactoryItems as $stateFactoryItem) {
                $stateFactoryItemInstance = StateFactoryItemInstance::query()->create([
                    'state_factory_id' => $stateFactory->id,
                    'state_factory_item_id' => $stateFactoryItem->id,
                    'state_factory_instance_id' => $stateFactoryInstance->id,
                    'name' => $stateFactoryItem->name,
                    'sort' => $stateFactoryItem->sort,
                    'condition_type' => $stateFactoryItem->condition_type,
                    'status' => 0
                ]);

                $stateFactoryItemPersons = $stateFactoryItem->stateFactoryItemPersons;
                if ($stateFactoryItemPersons && count($stateFactoryItemPersons)) {
                    foreach ($stateFactoryItemPersons as $stateFactoryItemPerson) {
                        StateFactoryItemPersonInstance::query()->create([
                            'state_factory_item_id' => $stateFactoryItem->id,
                            'state_factory_item_person_id' => $stateFactoryItemPerson->id,
                            'state_factory_item_instance_id' => $stateFactoryItemInstance->id,
                            'company_user_id' => $stateFactoryItemPerson->company_user_id,
                            'company_id' => $stateFactory->company_id
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

        $stateFactoryInstance = StateFactoryInstance::query()->where(['modelable_type' => $modelType, 'modelable_id' => $modelId])->first();
        if (!$stateFactoryInstance) {
            throw new InvalidRequestException('自定义流程实例不存在');
        }
        if ($stateFactoryInstance->status !== 1) {
            throw new InvalidRequestException('自定义流程实例状态错误或已完成');
        }

        $waitingCount = StateFactoryItemInstance::query()->where(['state_factory_instance_id' => $stateFactoryInstance->id, 'status' => 0])->count();
        $inProgressCount = StateFactoryItemInstance::query()->where(['state_factory_instance_id' => $stateFactoryInstance->id, 'status' => 1])->count();

        // 找到最近一条还没开始的 instance
        $currentStateFactoryItemInstance = StateFactoryItemInstance::query()->where(['state_factory_instance_id' => $stateFactoryInstance->id, 'status' => 0])->orderBy('sort')->first();
        if ($currentStateFactoryItemInstance) {
            // 首次分配
            if ($waitingCount > 0 && $inProgressCount <= 0) {
                $currentStateFactoryItemInstance->status = 1;
                $currentStateFactoryItemInstance->save();

                return $currentStateFactoryItemInstance;
            }
        }


        // 找到最近的一条正在进行的 instance
        $currentStateFactoryItemInstance = StateFactoryItemInstance::query()->where(['state_factory_instance_id' => $stateFactoryInstance->id, 'status' => 1])->orderBy('sort')->first();
        if (!$currentStateFactoryItemInstance) {
            throw new InvalidRequestException('该实例已运行完成');
        }

        // 有审核的完成条件
        if ($currentStateFactoryItemInstance->condition_type) {
            // 审核人
            $stateFactoryItemPersonInstances = StateFactoryItemPersonInstance::query()->where(['state_factory_item_instance_id' => $currentStateFactoryItemInstance->id])->get();
            if ($stateFactoryItemPersonInstances && count($stateFactoryItemPersonInstances)) {
                $approveCount = StateFactoryItemPersonInstance::query()->where(['state_factory_item_instance_id' => $currentStateFactoryItemInstance->id, 'result' => 1])->count();

                switch ($currentStateFactoryItemInstance->condition_type) {
                    case 1: // 需要全部完成
                        if ($approveCount !== count($stateFactoryItemPersonInstances)) {
                            throw new InvalidRequestException('该流程还有待审批的事件未完成');
                        }
                        break;
                    case 2: // 任一完成
                        if ($approveCount === 0) {
                            throw new InvalidRequestException('该流程还未通过任一审核员的审批');
                        }
                        break;
                }
            }
        }
        // 将该条实例置为 已完成
        $currentStateFactoryItemInstance->status = 2;
        $currentStateFactoryItemInstance->save();

        $next = StateFactoryItemInstance::query()->where(['state_factory_instance_id' => $stateFactoryInstance->id, 'status' => 0])->orderBy('sort')->first();
        if (!$next) {
            // 将整个实例置为 已完成
            $stateFactoryInstance->status = 2;
            $stateFactoryInstance->save();
        } else {
            // 下一个实例置为 开始
            $next->status = 1;
            $next->save();
        }

        return $next;
    }
}
