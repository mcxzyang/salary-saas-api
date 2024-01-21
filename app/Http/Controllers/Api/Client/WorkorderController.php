<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\WorkingProcess;
use App\Models\Workorder;
use App\Models\WorkorderTask;
use App\Models\WorkorderTaskUser;
use Illuminate\Http\Request;

class WorkorderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Workorder::filter($request->all())
            ->with(['goods'])
            ->where('is_deleted', 0)
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Workorder $workorder)
    {
        $this->authorize('own', $workorder);

        return $this->success(new BaseResource($workorder->load(['workorderTasks.workingProcess', 'workorderTasks.workingProcessChargeUser', 'workorderTasks.reportWorkingChargeUser', 'goods'])));
    }

    public function store(Request $request, Workorder $workorder)
    {
        $this->validate($request, [
            'goods_id' => 'required',
            'planned_number' => 'required',
            'plan_start_at' => 'required',
            'plan_end_at' => 'required'
        ]);

        $params = $request->all();

        $user = auth('client')->user();

        $workorder->fill(array_merge($params, ['company_id' => $user->company_id]));
        $workorder->save();

        if (isset($params['working_process_ids']) && count($params['working_process_ids'])) {
            foreach ($params['working_process_ids'] as $working_process_id) {
                $workingProcess = WorkingProcess::query()->where(['id' => $working_process_id, 'company_id' => $user->company_id])->first();
                if ($workingProcess) {

                    $workorderTask = WorkorderTask::query()->create([
                        'workorder_id' => $workorder->id,
                        'name' => $workingProcess->name,
                        'no' => $workingProcess->no,
                        'working_process_id' => $workingProcess->id,
                        'report_working_rate' => $workingProcess->report_working_rate,
                        'report_working_permission' => $workingProcess->report_working_permission,
                        'plan_number' => $workorder->planned_number,
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

        return $this->message('操作成功');
    }

    public function update(Request $request, Workorder $workorder)
    {
        $this->authorize('own', $workorder);

        $params = $request->all();

        $workorder->fill($params);
        $workorder->save();

        $user = auth('client')->user();

        $workorderTaskIds = [];
        if (isset($params['working_process_ids']) && count($params['working_process_ids'])) {
            foreach ($params['working_process_ids'] as $working_process_id) {
                $workingProcess = WorkingProcess::query()->where(['id' => $working_process_id, 'company_id' => $user->company_id])->first();
                if ($workingProcess) {
                    $workorderTask = WorkorderTask::query()->firstOrCreate([
                        'workorder_id' => $workorder->id,
                        'name' => $workingProcess->name,
                        'no' => $workingProcess->no
                    ]);
                    $workorderTaskIds[] = $workorderTask->id;
                }
            }
        }
        WorkorderTask::query()->where('workorder_id', $workorder->id)->whereNotIn('id', $workorderTaskIds)->delete();

        return $this->message('操作成功');
    }

    public function destroy(Workorder $workorder)
    {
        $this->authorize('own', $workorder);

        $workorder->is_deleted = 0;
        $workorder->save();

        return $this->message('操作成功');
    }
}
