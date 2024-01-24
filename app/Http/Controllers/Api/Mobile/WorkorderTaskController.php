<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\WorkorderTask;
use App\Models\WorkorderTaskUser;
use Illuminate\Http\Request;

class WorkorderTaskController extends Controller
{
    public function index()
    {
        $user = auth('client')->user();

        $taskIds = WorkorderTaskUser::query()->where(['company_user_id' => $user->id])->pluck('workorder_task_id');

        $list = WorkorderTask::query()->whereIn('id', $taskIds)
            ->with(['workorder.goods', 'workingProcess'])
            ->where(['status' => 1])
            ->orderBy('id', 'desc')
            ->paginateOrGet();

        return $this->success(BaseResource::collection($list));
    }
}