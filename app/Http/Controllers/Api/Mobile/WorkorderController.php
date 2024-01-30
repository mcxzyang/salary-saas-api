<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Workorder;

class WorkorderController extends Controller
{
    public function show(Workorder $workorder)
    {
        $this->authorize('own', $workorder);

        return $this->success(new BaseResource($workorder->load(['workorderTasks.workorderTaskReports'])));
    }
}
