<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\StateFactoryItemPersonInstance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StateFactoryItemPersonInstanceController extends Controller
{
    public function index()
    {
        $user = auth('client')->user();

        $list = StateFactoryItemPersonInstance::query()
            ->leftJoin('state_factory_item_instances', 'state_factory_item_instances.id', '=', 'state_factory_item_person_instances.state_factory_item_instance_id')
            ->leftJoin('state_factory_instances', 'state_factory_instances.id', '=', 'state_factory_item_instances.state_factory_instance_id')
            ->where('state_factory_instances.model_type', 'orders')
            ->where('state_factory_item_person_instances.company_user_id', $user->id)
            ->where('state_factory_item_instances.status', 1)
            ->where('state_factory_instances.status', 1)
            ->select(
                [
                    DB::raw('state_factory_item_person_instances.id as state_factory_item_person_instance_id'), 'state_factory_item_person_instances.result', 'state_factory_item_person_instances.reject_reason', 'state_factory_item_person_instances.approve_at', 'state_factory_item_person_instances.remark',
                    DB::raw('approve_instances.model_id as order_id'), 'state_factory_item_instances.name'
                ]
            )->paginateOrGet();
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

        return $this->message('操作成功');
    }
}
