<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\WorkorderTask;
use App\Models\WorkorderTaskReport;
use App\Models\WorkorderTaskUser;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function workorderTask(Request $request)
    {
        $user = auth('client')->user();

        // 报工数
        $reportNumber = WorkorderTaskReport::filter($request->all())
            ->where(['created_by' => $user->id])
            ->sum('good_product_number');

        $taskIds = WorkorderTaskUser::query()->where(['company_user_id' => $user->id])->pluck('workorder_task_id');
        // 派工数
        $dispatchNumber = WorkorderTask::filter($request->all())->whereIn('id', $taskIds)
            ->sum('plan_number');

        $finishedPer = $dispatchNumber > 0 ? round($reportNumber / $dispatchNumber, 2) : 0;

        $commissionAmountTotal = WorkorderTaskReport::filter($request->all())
            ->where(['created_by' => $user->id, 'approve_result' => 1])
            ->sum('commission_amount');

        return $this->success([
            'reportNumber' => $reportNumber * 1,
            'dispatchNumber' => $dispatchNumber * 1,
            'finishedPer' => number_format($finishedPer, 2),
            'commissionAmountTotal' => $commissionAmountTotal * 1
        ]);
    }
}
