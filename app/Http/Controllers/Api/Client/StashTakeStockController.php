<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\StashTakeStock;
use App\Models\StashTakeStockItem;
use Illuminate\Http\Request;

class StashTakeStockController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = StashTakeStock::filter($request->all())
            ->with(['createdUser', 'stash', 'stashTakeStockItems.goods'])
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(StashTakeStock $stashTakeStock)
    {
        $this->authorize('own', $stashTakeStock);

        return $this->success(new BaseResource($stashTakeStock->load(['createdUser', 'stash', 'stashTakeStockItems.goods'])));
    }

    public function store(Request $request, StashTakeStock $stashTakeStock)
    {
        $this->validate($request, [
            'stash_id' => 'required',
        ]);

        $user = auth('client')->user();

        $stashTakeStock->fill(array_merge($request->all(), ['company_id' => $user->company_id, 'created_by' => $user->id, 'status' => 1]));
        $stashTakeStock->save();

        $params = $request->all();

        if (isset($params['stash_take_stock_items']) && count($params['stash_take_stock_items'])) {
            foreach ($params['stash_take_stock_items'] as $item) {
                StashTakeStockItem::query()->create(array_merge($item, [
                    'company_id' => $user->company_id,
                    'stash_take_stock_id' => $stashTakeStock->id,
                    'status' => 1
                ]));
            }
        }

        return $this->message('操作成功');
    }

    public function update(Request $request, StashTakeStock $stashTakeStock)
    {
        $this->authorize('own', $stashTakeStock);

        $params = $request->all();
        $stashTakeStock->fill($params);
        $stashTakeStock->save();

        $user = auth('client')->user();

        $itemIds = [];
        if (isset($params['stash_take_stock_items']) && count($params['stash_take_stock_items'])) {
            foreach ($params['stash_take_stock_items'] as $item) {
                $stashTakeStockItem = new StashTakeStockItem([
                    'company_id' => $user->company_id,
                    'stash_take_stock_id' => $stashTakeStock->id,
                    'status' => 1
                ]);
                if (isset($item['id']) && $item['id']) {
                    $stashTakeStockItem = StashTakeStockItem::query()->where('id', $item['id'])->first();
                }
                $stashTakeStockItem->fill($item);
                $stashTakeStockItem->save();

                $itemIds[] = $stashTakeStockItem->id;
            }
        }
        StashTakeStockItem::query()->where(['stash_take_stock_id' => $stashTakeStock->id])->whereNotIn('id', $itemIds)->delete();

        return $this->message('操作成功');
    }

    public function destroy(StashTakeStock $stashTakeStock)
    {
        $this->authorize('own', $stashTakeStock);

        $stashTakeStock->delete();

        return $this->message('操作成功');
    }
}
