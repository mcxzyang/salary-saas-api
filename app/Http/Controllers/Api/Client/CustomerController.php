<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Customer;
use App\Models\CustomFieldModule;
use App\Models\CustomFieldModuleContent;
use App\Models\CustomModule;
use App\Services\CustomFieldService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Customer::filter($request->all())
            ->with(['customFieldModuleContents','inChargeCompanyUser', 'customerStatus', 'customerType', 'level', 'source', 'ripeness', 'industry'])
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Customer $customer)
    {
        $this->authorize('own', $customer);

        return $this->success(new BaseResource($customer->load(['customFieldModuleContents','inChargeCompanyUser', 'customerStatus', 'customerType', 'level', 'source', 'ripeness', 'industry', 'followUps'])));
    }

    public function store(Request $request, Customer $customer)
    {
        $this->validate($request, [
            'type' => 'required|numeric',
            'name' => 'required',
//            'phone' => 'required',
            'custom_fields' => 'array'
        ]);

        $user = auth('client')->user();

        $params = $request->all();

        $customer->fill(array_merge($params, ['company_id' => $user->company_id, 'status' => 1]));
        $customer->save();

        app(CustomFieldService::class)->createOrUpdate($params['$customFields'] ?? [], CustomModule::CODE_CUSTOMER, $customer->id);

        return $this->message('操作成功');
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorize('own', $customer);

        $params = $request->all();

        $customer->fill($params);
        $customer->save();

        app(CustomFieldService::class)->createOrUpdate($params['$customFields'] ?? [], CustomModule::CODE_CUSTOMER, $customer->id);

        return $this->message('操作成功');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return $this->message('操作成功');
    }
}
