<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CustomFieldType;
use Illuminate\Http\Request;

class CustomFieldTypeController extends Controller
{
    public function index()
    {
        $list = CustomFieldType::query()
            ->orderBy('id')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function store(Request $request, CustomFieldType $customFieldType)
    {
        $params = $request->all();

        $customFieldType->fill($params);
        $customFieldType->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, CustomFieldType $customFieldType)
    {
        $params = $request->all();

        $customFieldType->fill($params);
        $customFieldType->save();

        return $this->message('操作成功');
    }

    public function destroy(CustomFieldType $customFieldType)
    {
        $customFieldType->delete();

        return $this->message('操作成功');
    }
}
