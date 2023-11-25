<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $list = Company::filter($request->all())
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function store(Request $request, Company $company)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $params = $request->all();

        $company->fill($params);
        $company->save();

        return $this->success('操作成功');
    }

    public function update(Request $request, Company $company)
    {
        $params = $request->all();

        $company->fill($params);
        $company->save();

        return $this->success('操作成功');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return $this->success('操作成功');
    }
}
