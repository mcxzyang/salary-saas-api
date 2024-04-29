<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\WorkingProcess;
use App\Models\WorkingProcessApproveUser;
use App\Models\WorkingProcessReportUser;
use Illuminate\Http\Request;

class WorkingProcessController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = WorkingProcess::filter($request->all())
            ->where('company_id', $user->company_id)
            ->with(['createdUser', 'defectives', 'approveUsers', 'reportUsers'])
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(WorkingProcess $workingProcess)
    {
        $this->authorize('own', $workingProcess);

        return $this->success(new BaseResource($workingProcess->load(['createdUser', 'defectives', 'approveUsers', 'reportUsers'])));
    }

    public function store(Request $request, WorkingProcess $workingProcess)
    {
        $params = $this->validate($request, [
            'name' => 'required',
            'approve_company_user_id' => 'required|array',
            'report_working_permission' => 'array',
        ], [
            'name.required' => '请填写工序名称',
            'approve_company_user_id.required' => '请选择报工审批人'
        ]);

        $user = auth('client')->user();

        $workingProcess->fill(array_merge($params, ['company_id' => $user->company_id, 'created_by' => $user->id, 'status' => 1]));
        $workingProcess->save();

        $defectives = $request->input('defective_ids', []);

        $workingProcess->defectives()->sync($defectives);

        $workingProcess->reportUsers()->sync($request->input('report_working_permission', []));
        $workingProcess->approveUsers()->sync($request->input('approve_company_user_id', []));

        return $this->message('操作成功');
    }

    public function update(Request $request, WorkingProcess $workingProcess)
    {
        $this->authorize('own', $workingProcess);

        $params = $request->all();
        $workingProcess->fill($params);
        $workingProcess->save();

        $defectives = $request->input('defective_ids', []);

        $workingProcess->defectives()->sync($defectives);

        $workingProcess->reportUsers()->sync($request->input('report_working_permission', []));
        $workingProcess->approveUsers()->sync($request->input('approve_company_user_id', []));

        return $this->message('操作成功');
    }

    public function destroy(WorkingProcess $workingProcess)
    {
        $this->authorize('own', $workingProcess);

        $workingProcess->delete();

        WorkingProcessReportUser::query()->where('working_process_id', $workingProcess->id)->delete();
        WorkingProcessApproveUser::query()->where('working_process_id', $workingProcess->id)->delete();

        return $this->message('操作成功');
    }
}
