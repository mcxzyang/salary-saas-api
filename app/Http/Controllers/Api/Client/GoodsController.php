<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CustomFieldModule;
use App\Models\CustomFieldModuleContent;
use App\Models\CustomModule;
use App\Models\Goods;
use App\Services\CustomFieldService;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Goods::filter($request->all())
            ->where('is_deleted', 0)
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Goods $goods)
    {
        $this->authorize('own', $goods);

        return $this->success(new BaseResource($goods->load(['workingTechnology.workingTechnologyItems.workingProcess', 'goodsCategory'])));
    }

    public function store(Request $request, Goods $goods)
    {
        $this->validate($request, [
            'name' => 'required',
            'unit' => 'required',
            'type' => 'required|in:1,2',
            'goods_category_id' => 'required'
        ]);

        $params = $request->all();

        $user = auth('client')->user();

        $goods->fill(array_merge($params, ['company_id' => $user->company_id, 'status' => 1]));
        $goods->save();

        app(CustomFieldService::class)->createOrUpdate($params['$customFields'] ?? [], CustomModule::CODE_GOODS, $goods->id);

        return $this->message('操作成功');
    }

    public function update(Request $request, Goods $goods)
    {
        $this->authorize('own', $goods);

        $params = $request->all();

        $goods->fill($params);
        $goods->save();

        app(CustomFieldService::class)->createOrUpdate($params['$customFields'] ?? [], CustomModule::CODE_GOODS, $goods->id);

        return $this->message('操作成功');
    }

    public function destroy(Goods $goods)
    {
        $this->authorize('own', $goods);

        $goods->is_deleted = 1;
        $goods->save();

        return $this->message('操作成功');
    }
}
