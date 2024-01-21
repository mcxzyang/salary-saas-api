<?php
/**
 * This file is part of the finance
 *
 * (c) cherrybeal <mcxzyang@gmail.com>
 *
 * This source file is subject to the MIT license is bundled
 * with the source code in the file LICENSE
 */

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CompanyUser;
use Illuminate\Http\Request;

class AuthController extends Controller
{
//    public function testLogin()
//    {
//        $user = User::query()->find(1);
//        $token = auth('mobile')->login($user);
//        return $this->success($this->respondWithToken($token));
//    }

    public function login(Request $request)
    {
        $params = $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = CompanyUser::query()->where(['username' => $params['username']])->orWhere(['phone' => $params['username']])->first();
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

    public function me()
    {
        $user = auth('client')->user();

        return $this->success(new BaseResource($user->load(['company'])));
    }
}
