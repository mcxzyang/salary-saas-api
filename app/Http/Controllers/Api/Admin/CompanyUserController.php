<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CompanyUser;
use Illuminate\Http\Request;

class CompanyUserController extends Controller
{
    public function index(Request $request)
    {
        $list = CompanyUser::filter($request->all())
            ->with(['company'])
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(CompanyUser $companyUser)
    {
        return $this->success(new BaseResource($companyUser));
    }

    public function store(Request $request, CompanyUser $companyUser)
    {
        $this->validate($request, [
            'company_id' => 'required',
            'name' => 'required',
            'username' => 'required|unique:company_users,username',
            'password' => 'required|min:6'
        ], [
            'username.unique' => '该用户名已存在',
            'password.min' => '密码的最小长度为 6'
        ]);

        $companyUser->fill($request->all());
        $companyUser->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, CompanyUser $companyUser)
    {
        $params = $request->only(['username', 'name', 'phone', 'is_super_admin', 'status']);
        if (isset($params['username']) && $params['username']) {
            $usernameCheck = CompanyUser::query()->where('username', $params['username'])->where('id', '!=', $companyUser->id)->first();
            if ($usernameCheck) {
                return $this->failed('该用户名已被使用');
            }
        }
        if (isset($params['phone']) && $params['phone']) {
            $phoneCheck = CompanyUser::query()->where('phone', $params['phone'])->where('id', '!=', $companyUser->id)->first();
            if ($phoneCheck) {
                return $this->failed('该手机号码已被使用');
            }
        }

        $companyUser->fill($params);
        $companyUser->save();

        return $this->message('操作成功');
    }

    public function destroy(CompanyUser $companyUser)
    {
        $companyUser->delete();

        return $this->message('操作成功');
    }
}
