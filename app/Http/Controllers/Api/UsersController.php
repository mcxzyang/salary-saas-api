<?php
/**
 * This file is part of the ${PROJECT_NAME}.
 *
 * (c) cherrybeal <mcxzyang@gmail.com>
 *
 * This source file is subject to the MIT license is bundled
 * with the source code in the file LICENSE
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $list = User::query()->latest()->paginate($request->input('page', 10));

        return UserResource::collection($list);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        return UserResource::make($user);
    }
}
