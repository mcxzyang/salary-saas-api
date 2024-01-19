<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Admin\CreateCompanyOptionRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CompanyOption;
use Illuminate\Http\Request;

class CompanyOptionController extends Controller
{
    public function index(Request $request)
    {
        $list = CompanyOption::filter($request->all())
            ->with(['customModule'])
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(CompanyOption $companyOption)
    {
        // $this->authorize('own', $companyOption);

        return $this->success(new BaseResource($companyOption));
    }


    public function store(CreateCompanyOptionRequest $request, CompanyOption $companyOption)
    {
        $params = $request->all();

        $companyOption->fill($params);
        $companyOption->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, CompanyOption $companyOption)
    {
        // $this->authorize('own', $companyOption);

        $params = $request->all();

        $companyOption->fill($params);
        $companyOption->save();

        return $this->message('操作成功');
    }

    public function destroy(CompanyOption $companyOption)
    {
        // $this->authorize('own', $companyOption);

        $companyOption->delete();

        return $this->message('操作成功');
    }
}
