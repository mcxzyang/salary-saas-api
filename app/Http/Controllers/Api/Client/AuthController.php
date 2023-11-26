<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\CompanyUser;
use Illuminate\Http\Request;

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
}
