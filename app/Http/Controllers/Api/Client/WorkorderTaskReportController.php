<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\WorkorderTaskReport;
use Illuminate\Http\Request;

class WorkorderTaskReportController extends Controller
{
    public function index()
    {
        $user = auth('client')->user();

        $list = WorkorderTaskReport::query()
            ->with(['workorder.goods', 'workorderTask', 'productPerson', 'createdUser', 'approveCompanyUser'])
            // ->where('approve_company_user_id', $user->id)
            // ->where('approve_result', 0)
            ->orderBy('id', 'desc')
            ->paginateOrGet();

        return $this->success(BaseResource::collection($list));
    }

    public function show(WorkorderTaskReport $workorderTaskReport)
    {
        $this->authorize('own', $workorderTaskReport);

        return $this->success(new BaseResource($workorderTaskReport->load(['workorder.goods', 'workorderTask', 'productPerson', 'createdUser', 'approveCompanyUser'])));
    }

    public function audit(Request $request, WorkorderTaskReport $workorderTaskReport)
    {
        $params = $this->validate($request, [
            'approve_result' => 'required|in:1,2'
        ]);
        $this->authorize('own', $workorderTaskReport);
        if ($workorderTaskReport->approve_result !== 0) {
            return $this->failed('已被操作');
        }

        $workorderTaskReport->fill($params);
        $workorderTaskReport->save();

        if ($workorderTaskReport->approve_result === 1) {
            $workorderTask = $workorderTaskReport->workorderTask;
            if ($workorderTask) {
                $reportCallNumberNowCount = WorkorderTaskReport::query()->where('workorder_task_id', $params['workorder_task_id'])->sum('report_call_number');
                if ($workorderTask->status === 1 && $reportCallNumberNowCount >= $workorderTask->plan_number) {
                    $workorderTask->status = 2;
                }
                if (!$workorderTask->actual_start_at || !$workorderTask->actual_end_at) {
                    $workorderTask->actual_start_at = $workorderTaskReport->start_at;
                    $workorderTask->actual_end_at = $workorderTaskReport->end_at;
                }

                $workorderTask->ungood_score_number += $workorderTaskReport->ungood_product_number;
                $workorderTask->good_score_number += $workorderTaskReport->good_product_number;
                $workorderTask->save();
            }
        }

        return $this->message('操作成功');
    }
}
