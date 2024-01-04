<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Jobs\ClientOperateLogJob;
use App\Models\CompanyMenu;
use App\Models\CompanyRoleMenu;
use App\Models\CompanyUser;
use App\Models\CompanyUserToken;
use App\Services\ClientOperateLogService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $params = $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha_api:'.request('key').',math'
        ], [
            'captcha.captcha_api' => '验证码错误'
        ]);
        $user = CompanyUser::query()->where(['username' => $params['username']])->first();
        if (!$user) {
            return $this->failed('账号不存在');
        }
        if (!\Hash::check($params['password'], $user->password)) {
            return $this->failed('密码不正确');
        }
        $token = auth('client')->login($user);

        $companyUserToken = CompanyUserToken::query()->where(['company_user_id' => $user->id])->first();
        if ($companyUserToken && $companyUserToken->token) {
            try {
                \JWTAuth::setToken($companyUserToken->token)->invalidate();
            } catch (TokenExpiredException $e) {
                //因为让一个过期的token再失效，会抛出异常，所以我们捕捉异常，不需要做任何处理
            }
        }
        CompanyUserToken::query()->create([
            'company_user_id' => $user->id,
            'token' => $token
        ]);

        app(ClientOperateLogService::class)->save($user, '登录', sprintf('用户名：%s - 账号登录', $user->username));

        return $this->success($this->respondWithToken($token));
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('client')->factory()->getTTL() * 60
        ];
    }

    public function menu()
    {
        $user = auth('client')->user();

        // menu
        $query = CompanyMenu::query()
            ->orderBy('sort')
            ->where('status', 1)
            ->where('type', 1);

        $permissionQuery = CompanyMenu::query()
            ->orderBy('sort')
            ->where('type', 2)
            ->where('status', 1);

        if (!$user->is_super_admin) {
            $menuIds = CompanyRoleMenu::query()->whereIn('company_role_id', $user->roles)->pluck('company_menu_id');
            $query->whereIn('id', $menuIds);

            $permissionQuery->whereIn('id', $menuIds);
        }
        $list = $query->get();

        // permissions
        $permissions = $permissionQuery->pluck('permission');

        return $this->success([
            'menu' => $list,
            'permission' => $permissions
        ]);
    }

    public function me()
    {
        $user = auth('client')->user();

        return $this->success(new BaseResource($user->load(['company'])));
    }
}
