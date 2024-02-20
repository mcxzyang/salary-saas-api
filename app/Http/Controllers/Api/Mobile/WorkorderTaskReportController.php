<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\WorkorderTask;
use App\Models\WorkorderTaskReport;
use App\Models\WorkorderTaskReportDefective;
use Illuminate\Http\Request;

class WorkorderTaskReportController extends Controller
{
    public function index()
    {
        $user = auth('client')->user();

        $list = WorkorderTaskReport::query()
            ->where('created_by', $user->id)
            ->with(['workorder.goods', 'workorderTask'])
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(WorkorderTaskReport $workorderTaskReport)
    {
        $user = auth('client')->user();

        if ($user->company_id !== $workorderTaskReport->workorder->company_id) {
            return $this->failed('权限错误');
        }
        return $this->success(new BaseResource($workorderTaskReport->load(['workorder.goods', 'workorderTask'])));
    }

    public function store(Request $request, WorkorderTaskReport $workorderTaskReport)
    {
        $this->validate($request, [
            'workorder_task_id' => 'required|integer',
            'product_person_id' => 'required|integer', // product_person_ids  改为  product_person_id  各自为自己的报工
            'report_call_number' => 'required|integer',
            'good_product_number' => 'required|integer'
        ]);

        $user = auth('client')->user();

        $params = $request->all();

        $workorderTask = WorkorderTask::query()->where('id', $params['workorder_task_id'])->first();
        if (!$workorderTask) {
            return $this->failed('记录不存在');
        }
        if ($workorderTask->status !== 1) {
            return $this->failed('当前状态不可进行报工');
        }
        $reportCallNumberCount = WorkorderTaskReport::query()->where(['workorder_task_id' => $params['workorder_task_id']])->whereIn('approve_result', [0, 1])->sum('report_call_number');
        $canApply= $workorderTask->plan_number - $reportCallNumberCount;

        if ($params['report_call_number'] > $canApply) {
            return $this->failed('当前报工数大于可报工数，当前可报工数为：'.$canApply);
        }
        // $workorderTaskReport->fill(array_merge($params, ['workorder_id' => $workorderTask->workorder_id, 'workorder_task_id' => $workorderTask->id, 'created_by' => $user->id, 'approve_company_user_id' => $workorderTask->approve_company_user_id]));
        $workorderTaskReport->fill(array_merge($params, ['workorder_id' => $workorderTask->workorder_id, 'workorder_task_id' => $workorderTask->id, 'created_by' => $user->id]));
        $workorderTaskReport->save();

        if (isset($params['defectives']) && count($params['defectives'])) {
            foreach ($params['defectives'] as $defective) {
                WorkorderTaskReportDefective::query()->create(array_merge([
                    'workorder_id' => $workorderTaskReport->workorder_id,
                    'workorder_task_id' => $workorderTaskReport->workorder_task_id,
                    'workorder_task_report_id' => $workorderTaskReport->id,
                ], $defective));
            }
        }

        return $this->message('操作成功');
    }

    public function update(Request $request, WorkorderTaskReport $workorderTaskReport)
    {
        $user = auth('client')->user();
        if ($user->id !== $workorderTaskReport->created_by) {
            return $this->failed('权限错误');
        }
        $params = $request->all();
        $workorderTaskReport->fill($params);
        $workorderTaskReport->save();

        $workorderTaskReportDefectiveIds = [];
        if (isset($params['defectives']) && count($params['defectives'])) {
            foreach ($params['defectives'] as $defective) {
                $workorderTaskReportDefective = new WorkorderTaskReportDefective([
                    'workorder_id' => $workorderTaskReport->workorder_id,
                    'workorder_task_id' => $workorderTaskReport->workorder_task_id,
                    'workorder_task_report_id' => $workorderTaskReport->id,
                ]);
                if (isset($defective['id']) && $defective['id']) {
                    $workorderTaskReportDefective = WorkorderTaskReportDefective::query()->where('id', $defective['id'])->first();
                }
                $workorderTaskReportDefective->fill($defective);
                $workorderTaskReportDefective->save();

                $workorderTaskReportDefectiveIds[] = $workorderTaskReportDefective->id;
            }
        }
        WorkorderTaskReportDefective::query()->where([
            'workorder_id' => $workorderTaskReport->workorder_id,
            'workorder_task_id' => $workorderTaskReport->workorder_task_id,
            'workorder_task_report_id' => $workorderTaskReport->id,
        ])->whereNotIn('id', $workorderTaskReportDefectiveIds)->delete();

        return $this->message('操作成功');
    }
}
