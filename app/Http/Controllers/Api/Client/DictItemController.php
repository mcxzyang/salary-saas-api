<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Dict;
use App\Models\DictItem;
use Illuminate\Http\Request;

class DictItemController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = DictItem::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();
        return $this->success(BaseResource::collection($list));
    }

    public function store(Request $request, DictItem $dictItem)
    {
        $this->validate($request, [
            'code' => 'required',
            'value' => 'required'
        ], [
            'value.required' => '请填写字典值'
        ]);

        $user = auth('client')->user();

        $params = $request->only(['code', 'value', 'sort', 'description']);

        $dict = Dict::query()->where('code', $params['code'])->first();
        if (!$dict) {
            return $this->failed('字典不存在');
        }

        $dictItem->fill(array_merge($params, ['is_system' => 0, 'company_id' => $user->company_id, 'is_deleted' => 0, 'dict_id' => $dict->id]));
        $dictItem->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, DictItem $dictItem)
    {
        $this->authorize('own', $dictItem);

        $params = $request->only(['value', 'sort', 'description']);

        $dictItem->fill($params);
        $dictItem->save();

        return $this->message('操作成功');
    }

    public function destroy(DictItem $dictItem)
    {
        $this->authorize('own', $dictItem);

        $dictItem->is_deleted = 1;
        $dictItem->save();

        return $this->message('操作成功');
    }
}
