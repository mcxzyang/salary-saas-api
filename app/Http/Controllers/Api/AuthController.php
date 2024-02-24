<?php
/**
 * This file is part of the laravel-template
 *
 * (c) cherrybeal <mcxzyang@gmail.com>
 *
 * This source file is subject to the MIT license is bundled
 * with the source code in the file LICENSE
 */

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::query()->where('email', $request->email)->first();
        if (!$user) {
            throw new InvalidRequestException('账号不存在');
        }
        if (!\Hash::check($request->password, $user->password)) {
            throw new InvalidRequestException('密码错误');
        }
        $token = auth('api')->login($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
