<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Stash;
use Illuminate\Http\Request;

class StashController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Stash::filter($request->all())
            ->with(['createdUser'])
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function store(Request $request, Stash $stash)
    {
        $user = auth('client')->user();

        $stash->fill(array_merge($request->all(), ['company_id' => $user->company_id, 'created_by' => $user->id]));
        $stash->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, Stash $stash)
    {
        $this->authorize('own', $stash);

        $stash->fill($request->all());
        $stash->save();

        return $this->message('操作成功');
    }

    public function destroy(Stash $stash)
    {
        $this->authorize('own', $stash);

        $stash->delete();

        return $this->message('操作成功');
    }
}
