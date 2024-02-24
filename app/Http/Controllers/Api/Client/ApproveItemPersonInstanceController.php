<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\ApproveItemPersonInstance;
use App\Services\ApproveService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApproveItemPersonInstanceController extends Controller
{
    public function index()
    {
        $user = auth('client')->user();

        $list = ApproveItemPersonInstance::query()
            ->with(['approveItemInstance.approveInstance.modelable.companyUser', 'approveItemInstance.approveInstance.modelable.customer'])
            ->whereHas('approveItemInstance', function ($query) {
                $query->whereHas('approveInstance', function ($q) {
                    $q->where('status', 1);
                })->where('status', 1);
            })
            ->where('company_user_id', $user->id)
            ->whereNull('result')
            ->paginateOrGet();
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

        if ($approveItemPersonInstance->result === 1 && $approveInstance) {
            try {
                $model = $approveInstance->modelable;
                $currentApproveItemInstance = app(ApproveService::class)->nextStep($model);
                if ($currentApproveItemInstance) {
                    $model->current_approve_item_instance_id = $currentApproveItemInstance->id;
                    $model->save();
                }
            } catch (\Exception $e) {
            }
        }

        return $this->message('操作成功');
    }
}
