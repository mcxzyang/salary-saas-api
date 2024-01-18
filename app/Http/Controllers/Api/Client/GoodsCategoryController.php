<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Requests\Client\CreateGoodsCategoryRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\GoodsCategory;
use Illuminate\Http\Request;

class GoodsCategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = GoodsCategory::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function tree()
    {
        $user = auth('client')->user();

        $list = GoodsCategory::query()
            ->where('company_id', $user->company_id)
            ->where('pid', 0)
            ->with(['allChildren'])
            ->get();
        return $this->success(BaseResource::collection($list));
    }

    public function show(GoodsCategory $goodsCategory)
    {
        $this->authorize('own', $goodsCategory);

        return $this->success(new BaseResource($goodsCategory->load(['parentCategory'])));
    }


    public function store(CreateGoodsCategoryRequest $request, GoodsCategory $goodsCategory)
    {
        $params = $request->all();

        $user = auth('client')->user();

        $goodsCategory->fill(array_merge($params, ['company_id' => $user->company_id]));
        $goodsCategory->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, GoodsCategory $goodsCategory)
    {
        $this->authorize('own', $goodsCategory);

        $params = $request->all();

        $goodsCategory->fill($params);
        $goodsCategory->save();

        return $this->message('操作成功');
    }

    public function destroy(GoodsCategory $goodsCategory)
    {
        $this->authorize('own', $goodsCategory);

        GoodsCategory::query()->where(['pid' => $goodsCategory->id])->delete();
        $goodsCategory->delete();

        return $this->message('操作成功');
    }
}
