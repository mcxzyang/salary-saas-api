<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\ApproveItemPersonInstance;
use App\Services\ApproveService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApproveItemPersonInstanceController extends Controller
{
    public function index()
    {
        $user = auth('client')->user();

        $list = ApproveItemPersonInstance::query()
            ->leftJoin('approve_item_instances', 'approve_item_instances.id', '=', 'approve_item_person_instances.approve_item_instance_id')
            ->leftJoin('approve_instances', 'approve_instances.id', '=', 'approve_item_instances.approve_instance_id')
            ->where('approve_instances.model_type', 'orders')
            ->where('approve_item_person_instances.company_user_id', $user->id)
            ->where('approve_item_instances.status', 1)
            ->where('approve_instances.status', 1)
            ->select(
                [
                    DB::raw('approve_item_person_instances.id as approve_item_person_instance_id'), 'approve_item_person_instances.result', 'approve_item_person_instances.reject_reason', 'approve_item_person_instances.approve_at', 'approve_item_person_instances.remark',
                    DB::raw('approve_instances.model_id as order_id')
                ]
            )->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function update(Request $request, ApproveItemPersonInstance $approveItemPersonInstance)
    {
        $this->authorize('own', $approveItemPersonInstance);

        $this->validate($request, [
            'result' => 'required|in:1,2'
        ]);

        $params = $request->all();
        if ($params['result'] == 1) {
            $params['approve_at'] = Carbon::now();
        }
        $approveItemPersonInstance->fill($params);
        $approveItemPersonInstance->save();

        $approveInstance = $approveItemPersonInstance->approveItemInstance->approveInstance;

        if ($approveItemPersonInstance->result === 1) {
            try {
                app(ApproveService::class)->nextStep($approveInstance->model_type, $approveInstance->model_id);
            } catch (\Exception $e) {
            }
        }

        return $this->message('操作成功');
    }
}
