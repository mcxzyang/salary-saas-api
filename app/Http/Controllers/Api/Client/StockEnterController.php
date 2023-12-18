<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\ProductSku;
use App\Models\StockEnter;
use App\Models\StockEnterItem;
use Illuminate\Http\Request;

class StockEnterController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = StockEnter::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(StockEnter $stockEnter)
    {
        $this->authorize('own', $stockEnter);

        return $this->success(new BaseResource($stockEnter->load(['stockEnterItems.product', 'stockEnterItems.productSku', 'type'])));
    }

    public function store(Request $request, StockEnter $stockEnter)
    {
        $this->validate($request, [
            'no' => 'required',
            'type_id' => 'required|numeric',
            'enter_at' => 'required|date',
            'stock_enter_items' => 'array'
        ]);

        $user = auth('client')->user();

        $params = $request->all();

        $stockEnter->fill(array_merge($params, ['company_id' => $user->company_id]));
        $stockEnter->save();

        if (isset($params['stock_enter_items']) && count($params['stock_enter_items'])) {
            foreach ($params['stock_enter_items'] as $stockEnterItem) {
                StockEnterItem::query()->create([
                    'stock_enter_id' => $stockEnter->id,
                    'company_id' => $user->company_id,
                    'product_id' => $stockEnterItem['product_id'],
                    'product_sku_id' => $stockEnterItem['product_sku_id'],
                    'number' => $stockEnterItem['number']
                ]);

                ProductSku::query()->where('id', $stockEnterItem['product_sku_id'])->increment('stock', $stockEnterItem['number']);
            }
        }

        return $this->message('操作成功');
    }

    public function update(Request $request, StockEnter $stockEnter)
    {
        $this->authorize('own', $stockEnter);

        $params = $request->all();

        $stockEnter->fill($params);
        $stockEnter->save();

        $user = auth('client')->user();

        $itemIds = [];
        if (isset($params['stock_enter_items']) && count($params['stock_enter_items'])) {
            foreach ($params['stock_enter_items'] as $item) {
                $stockEnterItem = new StockEnterItem(['stock_enter_id' => $stockEnter->id, 'company_id' => $user->company_id]);
                if (isset($item['stock_enter_item_id']) && $item['stock_enter_item_id']) {
                    $stockEnterItem = StockEnterItem::query()->where('id', $item['stock_enter_item_id'])->first();
                }
                $stockEnterItem->fill($item);
                $stockEnterItem->save();

                $itemIds[] = $stockEnterItem->id;
            }
        }
        StockEnterItem::query()->where('stock_enter_id', $stockEnter->id)->whereNotIn('id', $itemIds)->delete();

        return $this->message('操作成功');
    }

    public function destroy(StockEnter $stockEnter)
    {
        $this->authorize('own', $stockEnter);

        StockEnterItem::query()->where('stock_enter_id', $stockEnter->id)->delete();
        $stockEnter->delete();

        return $this->message('操作成功');
    }
}
