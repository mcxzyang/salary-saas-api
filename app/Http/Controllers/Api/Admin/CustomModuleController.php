<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CustomModule;
use Illuminate\Http\Request;

class CustomModuleController extends Controller
{
    public function index(Request $request)
    {
        $list = CustomModule::filter($request->all())
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function store(Request $request, CustomModule $customModule)
    {
        $this->validate($request, [
            'code' => 'required|unique:custom_modules,code',
            'name' => 'required'
        ]);

        $params = $request->all();

        $customModule->fill($params);
        $customModule->save();

        return $this->success('操作成功');
    }

    public function update(Request $request, CustomModule $customModule)
    {
        $params = $request->all();

        $customModule->fill($params);
        $customModule->save();

        return $this->success('操作成功');
    }

    public function destroy(CustomModule $customModule)
    {
        $customModule->delete();

        return $this->success('操作成功');
    }
}
