<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\StateFactoryItemPersonInstance;
use App\Services\StateFactoryService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StateFactoryItemPersonInstanceController extends Controller
{
    public function index()
    {
        $user = auth('client')->user();

        $list = StateFactoryItemPersonInstance::query()
            ->with(['stateFactoryItemInstance.stateFactoryInstance'])
            ->whereHas('stateFactoryItemInstance', function ($query) {
                $query->whereHas('stateFactoryInstance', function ($q) {
                    $q->where('status', 1);
                })->where('status', 1);
            })
            ->where('company_user_id', $user->id)
            ->whereNull('result')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function update(Request $request, StateFactoryItemPersonInstance $stateFactoryItemPersonInstance)
    {
        $this->authorize('own', $stateFactoryItemPersonInstance);

        $this->validate($request, [
            'result' => 'required|in:1,2'
        ]);

        $params = $request->all();
        if ($params['result'] == 1) {
            $params['approve_at'] = Carbon::now();
        }
        $stateFactoryItemPersonInstance->fill($params);
        $stateFactoryItemPersonInstance->save();

        $stateFactoryInstance = $stateFactoryItemPersonInstance->stateFactoryItemInstance->stateFactoryInstance;

        // 审核通过，自动进入下一步
        if ($stateFactoryItemPersonInstance->result === 1 && $stateFactoryInstance) {
            try {
                $model = $stateFactoryInstance->modelable;
                $currentStateFactoryItemInstance = app(StateFactoryService::class)->nextStep($model);
                if ($currentStateFactoryItemInstance) {
                    $model->current_state_factory_item_instance_id = $currentStateFactoryItemInstance->id;
                    $model->save();
                }
            } catch (\Exception $e) {
            }
        }

        return $this->message('操作成功');
    }
}
