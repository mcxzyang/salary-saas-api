<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\WorkingProcess;
use Illuminate\Http\Request;

class WorkingProcessController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = WorkingProcess::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(WorkingProcess $workingProcess)
    {
        $this->authorize('own', $workingProcess);

        return $this->success(new BaseResource($workingProcess->load(['createdUser', 'defectives'])));
    }

    public function store(Request $request, WorkingProcess $workingProcess)
    {
        $params = $this->validate($request, [
            'name' => 'required'
        ], [
            'name.required' => '请填写工序名称'
        ]);

        $user = auth('client')->user();

        $workingProcess->fill(array_merge($params, ['company_id' => $user->company_id, 'created_by' => $user->id, 'status' => 1]));
        $workingProcess->save();

        $defectives = $request->input('defective_ids', []);

        $workingProcess->defectives()->sync($defectives);

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

        return $this->message('操作成功');
    }

    public function destroy(WorkingProcess $workingProcess)
    {
        $this->authorize('own', $workingProcess);

        $workingProcess->delete();

        return $this->message('操作成功');
    }
}