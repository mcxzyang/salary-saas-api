<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Product::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Product $product)
    {
        $this->authorize('own', $product);

        return $this->success(new BaseResource($product->load(['productSkus', 'category'])));
    }

    public function store(Request $request, Product $product)
    {
        $this->validate($request, [
            'title' => 'required',
            'category_id' => 'required',
            'content' => 'required',
            'product_skus.*.sales_price' => 'numeric',
            'product_skus.*.original_price' => 'numeric',
            'product_skus.*.sku_number' => 'required',
            'product_skus.*.sku_name' => 'required',
            'product_skus.*.unit' => 'required',
        ], [
            'product_skus.*.sales_price.numeric' => '售价 只能为数字',
            'product_skus.*.original_price.numeric' => '市场价 只能为数字',
            'product_skus.*.sku_number.required' => '规格编码 不能为空',
            'product_skus.*.sku_name.required' => '规格名称 不能为空',
            'product_skus.*.unit.required' => '单位 不能为空',
        ]);

        $params = $request->all();

        $user = auth('client')->user();

        $product->fill(array_merge($params, ['company_id' => $user->company_id]));
        $product->save();

        if (isset($params['product_skus']) && count($params['product_skus'])) {
            foreach ($params['product_skus'] as $item) {
                $productSku = new ProductSku(['product_id' => $product->id, 'company_id' => $product->company_id]);
                $productSku->fill($item);
                $productSku->save();
            }
        }

        return $this->message('操作成功');
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('own', $product);

        $params = $request->all();

        $product->fill($params);
        $product->save();

        $productSkus = [];

        if (isset($params['product_skus']) && count($params['product_skus'])) {
            $this->validate($request, [
                'product_skus.*.sales_price' => 'numeric',
                'product_skus.*.original_price' => 'numeric',
                'product_skus.*.sku_number' => 'required',
                'product_skus.*.sku_name' => 'required',
                'product_skus.*.unit' => 'required',
            ], [
                'product_skus.*.sales_price.numeric' => '售价 只能为数字',
                'product_skus.*.original_price.numeric' => '市场价 只能为数字',
                'product_skus.*.sku_number.required' => '规格编码 不能为空',
                'product_skus.*.sku_name.required' => '规格名称 不能为空',
                'product_skus.*.unit.required' => '单位 不能为空',
            ]);
            foreach ($params['product_skus'] as $item) {
                $productSku = new ProductSku(['product_id' => $product->id, 'company_id' => $product->company_id]);
                if (isset($item['id']) && $item['id']) {
                    $productSku = ProductSku::query()->where([
                        'product_id' => $product->id, 'id' => $item['id'], 'company_id' => $product->company_id
                    ])->first();
                }
                $productSku->fill($item);
                $productSku->save();

                $productSkus[] = $productSku->id;
            }
        }
        ProductSku::query()->where(['product_id' => $product->id, 'company_id' => $product->company_id])->whereNotIn('id', $productSkus)->delete();

        return $this->message('操作成功');
    }

    public function destroy(Product $product)
    {
        $this->authorize('own', $product);

        $product->productSkus()->delete();
        $product->delete();

        return $this->message('删除成功');
    }
}
