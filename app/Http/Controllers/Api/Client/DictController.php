<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Dict;
use Illuminate\Http\Request;

class DictController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Dict::filter($request->all())
            ->with(['dictItems' => function ($query) use ($user) {
                $query->where('company_id', $user->company_id)
                    ->where('is_deleted', 0)
                    ->orderBy('sort')
                    ->orderBy('id');
            }])->get();

        return $this->success(BaseResource::collection($list));
    }
}
