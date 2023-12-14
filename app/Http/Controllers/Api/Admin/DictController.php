<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Dict;
use Illuminate\Http\Request;

class DictController extends Controller
{
    public function index(Request $request)
    {
        $list = Dict::filter($request->all())
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Dict $dict)
    {
        return $this->success(new BaseResource($dict));
    }

    public function store(Request $request, Dict $dict)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required|unique:dicts,code'
        ]);

        $dict->fill($request->all());
        $dict->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, Dict $dict)
    {
        $dict->fill($request->all());
        $dict->save();

        return $this->message('操作成功');
    }

    public function destroy(Dict $dict)
    {
        $dict->delete();

        return $this->message('操作成功');
    }
}
