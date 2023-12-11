<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Category::filter($request->all())
            ->with(['parentCategory'])
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function store(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $user = auth('client')->user();

        $category->fill(array_merge($request->all(), ['company_id' => $user->company_id]));
        $category->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('own', $category);

        $params = $request->all();

        $category->fill($params);
        $category->save();

        return $this->message('操作成功');
    }

    public function destroy(Category $category)
    {
        $this->authorize('own', $category);

        $category->delete();

        return $this->message('操作成功');
    }
}
