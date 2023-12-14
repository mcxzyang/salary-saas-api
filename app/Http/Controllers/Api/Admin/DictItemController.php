<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\DictItem;
use Illuminate\Http\Request;

class DictItemController extends Controller
{
    public function index(Request $request)
    {
        $list = DictItem::filter($request->all())
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(DictItem $dictItem)
    {
        return $this->success(new BaseResource($dictItem));
    }

    public function store(Request $request, DictItem $dictItem)
    {
        $this->validate($request, [
            'dict_id' => 'required',
            'value' => 'required',
            'sort' => 'required'
        ]);

        $dictItem->fill(array_merge($request->all(), ['is_system' => 1]));
        $dictItem->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, DictItem $dictItem)
    {
        $dictItem->fill($request->all());
        $dictItem->save();

        return $this->message('操作成功');
    }

    public function destroy(DictItem $dictItem)
    {
        $dictItem->delete();

        return $this->message('操作成功');
    }
}
