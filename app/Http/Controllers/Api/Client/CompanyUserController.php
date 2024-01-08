<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CompanyUser;
use Illuminate\Http\Request;

class CompanyUserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = CompanyUser::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(CompanyUser $companyUser)
    {
        $this->authorize('own', $companyUser);

        return $this->success(new BaseResource($companyUser->load(['roles', 'departments'])));
    }

    public function store(Request $request, CompanyUser $companyUser)
    {
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|unique:company_users,username',
            'password' => 'required|min:6',
            'phone' => 'required'
        ]);

        $params = $request->all();

        $user = auth('client')->user();

        $companyUser->fill(array_merge($params, ['company_id' => $user->company_id]));
        $companyUser->save();

        $companyUser->roles()->sync($params['roles'] ?? []);

        $companyUser->departments()->sync($params['departments'] ?? []);

        return $this->message('操作成功');
    }

    public function update(Request $request, CompanyUser $companyUser)
    {
        $this->authorize('own', $companyUser);

        $params = $request->only(['name', 'username', 'phone', 'is_super_admin', 'roles']);

        if (isset($params['username']) && $params['username']) {
            $checkResult = CompanyUser::query()->where('username', $params['username'])->where('id', '!=', $companyUser->id)->first();
            if ($checkResult) {
                return $this->failed('用户名已被使用');
            }
        }

        $companyUser->fill($params);
        $companyUser->save();

        if (isset($params['roles'])) {
            $companyUser->roles()->sync($params['roles']);
        }

        if (isset($params['departments'])) {
            $companyUser->departments()->sync($params['departments']);
        }

        return $this->message('操作成功');
    }

    public function destroy(CompanyUser $companyUser)
    {
        $this->authorize('own', $companyUser);

        $companyUser->delete();

        return $this->message('操作成功');
    }
}
