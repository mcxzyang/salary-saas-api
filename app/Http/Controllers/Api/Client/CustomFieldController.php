<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = CustomField::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('sort')
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function store(Request $request, CustomField $customField)
    {
        $this->validate($request, [
            'module_id' => 'required|numeric',
            'name' => 'required',
            'type' => 'required|numeric',
        ]);

        $user = auth('client')->user();

        $customField->fill(array_merge($request->all(), ['company_id' => $user->company_id]));
        $customField->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, CustomField $customField)
    {
        $this->authorize('own', $customField);

        $customField->fill($request->all());
        $customField->save();

        return $this->message('操作成功');
    }

    public function show(CustomField $customField)
    {
        $this->authorize('own', $customField);

        return $this->success(new BaseResource($customField));
    }

    public function destroy(CustomField $customField)
    {
        $this->authorize('own', $customField);

        $customField->delete();

        return $this->message('操作成功');
    }

    public function typeList()
    {
        $list = CustomField::$typeMap;
        $arr = [];
        foreach ($list as $key => $value) {
            $arr[] = [
                'id' => $key,
                'name' => $value
            ];
        }

        return $this->success($arr);
    }

    public function moduleList()
    {
        $list = CustomField::$moduleMap;
        $arr = [];
        foreach ($list as $key => $value) {
            $arr[] = [
                'id' => $key,
                'name' => $value
            ];
        }

        return $this->success($arr);
    }
}
