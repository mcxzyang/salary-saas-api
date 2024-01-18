<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Requests\Client\CreateFollowUpRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\FollowUp;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = FollowUp::filter($request->all())
            ->with(['type', 'customer', 'createdUser'])
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(FollowUp $followUp)
    {
        $this->authorize('own', $followUp);

        return $this->success(new BaseResource($followUp->load(['type', 'customer', 'createdUser'])));
    }


    public function store(CreateFollowUpRequest $request, FollowUp $followUp)
    {
        $params = $request->all();

        $user = auth('client')->user();

        $followUp->fill(array_merge($params, ['company_id' => $user->company_id]));
        $followUp->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, FollowUp $followUp)
    {
        $this->authorize('own', $followUp);

        $params = $request->all();

        $followUp->fill($params);
        $followUp->save();

        return $this->message('操作成功');
    }

    public function destroy(FollowUp $followUp)
    {
        $this->authorize('own', $followUp);

        $followUp->delete();

        return $this->message('操作成功');
    }
}
