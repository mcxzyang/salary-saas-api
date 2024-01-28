<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\WorkorderTask;
use App\Models\WorkorderTaskReport;
use Illuminate\Http\Request;

class WorkorderTaskReportController extends Controller
{
    public function index()
    {
        $user = auth('client')->user();

        $list = WorkorderTaskReport::query()
            ->where('created_by', $user->id)
            ->with(['workorder', 'workorderTask'])
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
        return $this->success(new BaseResource($workorderTaskReport->load(['workorder', 'workorderTask'])));
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
        $reportCallNumberCount = $workorderTaskReport::query()->where(['workorder_task_id' => $params['workorder_task_id']])->sum('report_call_number');
        if ($reportCallNumberCount + $params['report_call_number'] > $workorderTask->plan_number) {
            return $this->failed('当前报工数大于可报工数');
        }
        // $workorderTaskReport->fill(array_merge($params, ['workorder_id' => $workorderTask->workorder_id, 'workorder_task_id' => $workorderTask->id, 'created_by' => $user->id, 'approve_company_user_id' => $workorderTask->approve_company_user_id]));
        $workorderTaskReport->fill(array_merge($params, ['workorder_id' => $workorderTask->workorder_id, 'workorder_task_id' => $workorderTask->id, 'created_by' => $user->id]));
        $workorderTaskReport->save();

        $reportCallNumberNowCount = $workorderTaskReport::query()->where(['workorder_task_id' => $params['workorder_task_id']])->sum('report_call_number');
        if ($reportCallNumberNowCount >= $workorderTask->plan_number) {
            // actual_start_at：最早一次报工的start_at
            // actual_end_at：最后一次报工的end_at
            $workorderTask->status = 2;
            $workorderTask->save();
        }

        // 现在我在 workorder_task_reports 表加了  defectives  字段，新增了  workorder_task_report_defectives  表，需要将 传递的字段 defectives  存储到 workorder_task_report_defectives 表，以备后面对不良品项做统计和分析

        return $this->message('操作成功');
    }
}
