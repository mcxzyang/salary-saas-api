<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Customer;
use App\Models\CustomFieldModule;
use App\Models\CustomFieldModuleContent;
use App\Models\CustomModule;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Customer::filter($request->all())
            ->with(['customFieldModuleContents','inChargeCompanyUser', 'customerStatus', 'customerType', 'type', 'level', 'source', 'ripeness', 'industry'])
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Customer $customer)
    {
        $this->authorize('own', $customer);

        return $this->success(new BaseResource($customer->load(['customFieldModuleContents','inChargeCompanyUser', 'customerStatus', 'customerType', 'type', 'level', 'source', 'ripeness', 'industry'])));
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

        $customFields = $request->input('custom_fields');
        if (isset($customFields) && count($customFields)) {
            $customModule = CustomModule::query()->where('code', CustomModule::CODE_CUSTOMER)->first();
            foreach ($customFields as $customField) {
                if (isset($customField['custom_field_id']) && $customField['custom_field_id']) {
                    $customFieldModule = CustomFieldModule::query()->where(['custom_field_id' => $customField['custom_field_id'], 'custom_module_id' => $customModule->id])->first();
                    if ($customFieldModule && isset($customField['value']) && $customField['value']) {
                        CustomFieldModuleContent::query()->create([
                            'custom_module_id' => $customFieldModule->custom_module_id,
                            'custom_field_id' => $customFieldModule->custom_field_id,
                            'value' => $customField['value'],
                            'model_id' => $customer->id
                        ]);
                    }
                }
            }
        }

        return $this->message('操作成功');
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorize('own', $customer);

        $params = $request->all();

        $customer->fill($params);
        $customer->save();

        $customFields = $request->input('custom_fields');

        $customFieldModuleContentIds = [];

        $customModule = CustomModule::query()->where('code', CustomModule::CODE_CUSTOMER)->first();

        if (isset($customFields) && count($customFields)) {
            foreach ($customFields as $customField) {
                if (isset($customField['custom_field_id']) && $customField['custom_field_id']) {
                    $customFieldModule = CustomFieldModule::query()->where(['custom_field_id' => $customField['custom_field_id'], 'custom_module_id' => $customModule->id])->first();
                    if ($customFieldModule && isset($customField['value']) && $customField['value']) {
                        $customFieldModuleContent = new CustomFieldModuleContent([
                            'custom_module_id' => $customFieldModule->custom_module_id,
                            'custom_field_id' => $customFieldModule->custom_field_id,
                            'model_id' => $customer->id
                        ]);
                        if (isset($customField['custom_field_module_content_id']) && $customField['custom_field_module_content_id']) {
                            $customFieldModuleContent = CustomFieldModuleContent::query()->where('id', $customField['custom_field_module_content_id'])->first();
                        }
                        $customFieldModuleContent->fill($customField);
                        $customFieldModuleContent->save();

                        $customFieldModuleContentIds[] = $customFieldModuleContent->id;
                    }
                }
            }
        }
        CustomFieldModuleContent::query()->where(['custom_module_id' => $customModule->id, 'model_id' => $customer->id])
            ->whereNotIn('id', $customFieldModuleContentIds)
            ->delete();

        return $this->message('操作成功');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return $this->message('操作成功');
    }
}
