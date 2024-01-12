<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CompanyDepartment;
use App\Models\CompanyUserDepartment;
use App\Services\ClientOperateLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyDepartmentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = CompanyDepartment::filter($request->all())
            ->with(['allChildren.parentDepartment', 'parentDepartment'])
            ->where('company_id', $user->company_id)
            ->where('pid', 0)
            ->orderBy('id', 'desc')->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(CompanyDepartment $companyDepartment)
    {
        $user = auth('client')->user();
        if ($user->company_id !== $companyDepartment->company_id) {
            return $this->failed('权限错误');
        }
        return $this->success(new BaseResource($companyDepartment->load(['allChildren.parentDepartment', 'parentDepartment'])));
    }

    public function store(Request $request, CompanyDepartment $companyDepartment)
    {
        $this->validate($request, [
            'name' => 'required'
        ], [
            'name.required' => '请填写部门名称'
        ]);
        $user = auth('client')->user();

        $params = $request->all();

        $companyDepartment->fill(array_merge($params, ['company_id' => $user->company_id]));
        $companyDepartment->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, CompanyDepartment $companyDepartment)
    {
        $user = auth('client')->user();
        if ($user->company_id !== $companyDepartment->company_id) {
            return $this->failed('权限错误');
        }

        $params = $request->all();

        $companyDepartment->fill($params);
        $companyDepartment->save();

        return $this->message('操作成功');
    }

    public function destroy(CompanyDepartment $companyDepartment)
    {
        $user = auth('client')->user();
        if ($user->company_id !== $companyDepartment->company_id) {
            return $this->failed('权限错误');
        }

        DB::transaction(function () use ($companyDepartment, $user) {
            CompanyUserDepartment::query()->where('company_department_id', $companyDepartment->id)->delete();
            $companyDepartment->delete();
        });

        return $this->message('操作成功');
    }
}
