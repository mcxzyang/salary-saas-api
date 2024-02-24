<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CompanyRole;
use App\Models\CompanyUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyRoleController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = CompanyRole::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(CompanyRole $companyRole)
    {
        $user = auth('client')->user();
        if ($user->company_id !== $companyRole->company_id) {
            return $this->failed('权限错误');
        }
        return $this->success(new BaseResource($companyRole));
    }

    public function store(Request $request, CompanyRole $companyRole)
    {
        $this->validate($request, [
            'name' => 'required'
        ], [
            'name.required' => '请填写角色名称'
        ]);
        $user = auth('client')->user();

        $params = $request->all();

        $companyRole->fill(array_merge($params, ['company_id' => $user->company_id]));
        $companyRole->save();

        if (isset($params['menu_ids']) && count($params['menu_ids'])) {
            $companyRole->companyMenus()->sync($params['menu_ids']);
        }

        return $this->message('操作成功');
    }

    public function update(Request $request, CompanyRole $companyRole)
    {
        $user = auth('client')->user();
        if ($user->company_id !== $companyRole->company_id) {
            return $this->failed('权限错误');
        }

        $params = $request->all();

        $companyRole->fill($params);
        $companyRole->save();

        if (isset($params['menu_ids']) && count($params['menu_ids'])) {
            $companyRole->companyMenus()->sync($params['menu_ids']);
        }

        return $this->message('操作成功');
    }

    public function destroy(CompanyRole $companyRole)
    {
        $user = auth('client')->user();
        if ($user->company_id !== $companyRole->company_id) {
            return $this->failed('权限错误');
        }

        DB::transaction(function () use ($companyRole, $user) {
            CompanyUserRole::query()->where('company_role_id', $companyRole->id)->delete();
            $companyRole->delete();
        });

        return $this->message('操作成功');
    }
}
