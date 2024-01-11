<?php

namespace App\Http\Controllers\Api\Client;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Goods;
use App\Models\ProductSku;
use App\Models\Stock;
use App\Models\StockOut;
use App\Models\StockOutItem;
use Illuminate\Http\Request;

class StockOutController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = StockOut::filter($request->all())
            ->with(['stash', 'type'])
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(StockOut $stockOut)
    {
        $this->authorize('own', $stockOut);

        return $this->success(new BaseResource($stockOut->load(['stockOutItems.goods', 'type', 'stash'])));
    }

    public function store(Request $request, StockOut $stockOut)
    {
        $this->validate($request, [
            'stash_id' => 'required',
            'no' => 'required',
            'type_id' => 'required|numeric',
            'out_at' => 'required|date',
            'stock_out_items' => 'array'
        ]);

        $user = auth('client')->user();

        $params = $request->all();

        \DB::transaction(function () use ($stockOut, $params, $user) {
            $stockOut->fill(array_merge($params, ['company_id' => $user->company_id]));
            $stockOut->save();

            if (isset($params['stock_out_items']) && count($params['stock_out_items'])) {
                foreach ($params['stock_out_items'] as $stockOutItem) {
                    $stockOutItem = StockOutItem::query()->create([
                        'stock_out_id' => $stockOut->id,
                        'company_id' => $user->company_id,
                        'goods_id' => $stockOutItem['goods_id'],
                        'number' => $stockOutItem['number']
                    ]);

                    $stock = Stock::query()->where(['company_id' => $user->company_id, 'stash_id' => $stockOut->stash_id, 'goods_id' => $stockOutItem->goods_id])
                        ->where('number', '>=', $stockOutItem->number)->first();
                    if (!$stock) {
                        throw new InvalidRequestException('库存不足');
                    }
                    $stock->decrement('number', $stockOutItem['number']);
                }
            }
        });

        return $this->message('操作成功');
    }

    public function update(Request $request, StockOut $stockOut)
    {
        $this->authorize('own', $stockOut);

        $params = $request->all();

        $stockOut->fill($params);
        $stockOut->save();

        $user = auth('client')->user();

        $itemIds = [];
        if (isset($params['stock_out_items']) && count($params['stock_out_items'])) {
            foreach ($params['stock_out_items'] as $item) {
                $stockOutItem = new StockOutItem(['stock_out_id' => $stockOut->id, 'company_id' => $user->company_id]);
                if (isset($item['stock_enter_item_id']) && $item['stock_enter_item_id']) {
                    $stockOutItem = StockOutItem::query()->where('id', $item['stock_out_item_id'])->first();
                }
                $stockOutItem->fill($item);
                $stockOutItem->save();

                $itemIds[] = $stockOutItem->id;
            }
        }
        StockOutItem::query()->where('stock_out_id', $stockOut->id)->whereNotIn('id', $itemIds)->delete();

        return $this->message('操作成功');
    }

    public function destroy(StockOut $stockOut)
    {
        $this->authorize('own', $stockOut);

        StockOutItem::query()->where('stock_out_id', $stockOut->id)->delete();
        $stockOut->delete();

        return $this->message('操作成功');
    }
}
