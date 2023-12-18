<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Company;
use App\Models\DictItem;
use Illuminate\Http\Request;

class DictItemController extends Controller
{
    public function index(Request $request)
    {
        $list = DictItem::filter($request->all())
            ->where('is_system', 0)
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

        $companyList = Company::query()->get();
        foreach ($companyList as $company) {
            $newItem = $dictItem->replicate();
            $newItem->company_id = $company->id;
            $newItem->is_system = 0;
            $newItem->save();
        }

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
        DictItem::query()->where(['dict_id' => $dictItem->dict_id, 'value' => $dictItem->value])
            ->where('id', '!=', $dictItem->id)
            ->delete();

        $dictItem->delete();

        return $this->message('操作成功');
    }
}
