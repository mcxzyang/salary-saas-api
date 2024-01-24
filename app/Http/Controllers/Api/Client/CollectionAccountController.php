<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Requests\Client\CreateCollectionAccountRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CollectionAccount;
use Illuminate\Http\Request;

class CollectionAccountController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = CollectionAccount::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(CollectionAccount $collectionAccount)
    {
        $this->authorize('own', $collectionAccount);

        return $this->success(new BaseResource($collectionAccount));
    }


    public function store(CreateCollectionAccountRequest $request, CollectionAccount $collectionAccount)
    {
        $params = $request->all();

        $user = auth('client')->user();

        $collectionAccount->fill(array_merge($params, ['company_id' => $user->company_id]));
        $collectionAccount->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, CollectionAccount $collectionAccount)
    {
        $this->authorize('own', $collectionAccount);

        $params = $request->all();

        $collectionAccount->fill($params);
        $collectionAccount->save();

        return $this->message('操作成功');
    }

    public function destroy(CollectionAccount $collectionAccount)
    {
        $this->authorize('own', $collectionAccount);

        $collectionAccount->delete();

        return $this->message('操作成功');
    }
}
