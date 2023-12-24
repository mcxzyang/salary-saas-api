<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Stock::filter($request->all())
            ->with(['product', 'productSku'])
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Stock $stock)
    {
        $this->authorize('own', $stock);

        return $this->success(new BaseResource($stock->load(['product', 'productSku'])));
    }
}
