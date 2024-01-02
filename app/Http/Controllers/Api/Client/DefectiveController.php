<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CustomFieldModule;
use App\Models\CustomFieldModuleContent;
use App\Models\CustomModule;
use App\Models\Defective;
use Illuminate\Http\Request;

class DefectiveController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Defective::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Defective $defective)
    {
        $this->authorize('own', $defective);

        return $this->success(new BaseResource($defective));
    }

    public function store(Request $request, Defective $defective)
    {
        $this->validate($request, [
            'name' => 'required'
        ], [
            'name.required' => '请填写名称'
        ]);

        $user = auth('client')->user();

        $params = $request->all();

        $defective->fill(array_merge($params, ['company_id' => $user->company_id, 'status' => 1]));
        $defective->save();

        $customFields = $request->input('custom_fields');
        if (isset($customFields) && count($customFields)) {
            $customModule = CustomModule::query()->where('code', CustomModule::CODE_DEFECTIVE)->first();
            foreach ($customFields as $customField) {
                if (isset($customField['custom_field_id']) && $customField['custom_field_id']) {
                    $customFieldModule = CustomFieldModule::query()->where(['custom_field_id' => $customField['custom_field_id'], 'custom_module_id' => $customModule->id])->first();
                    if ($customFieldModule && isset($customField['value']) && $customField['value']) {
                        CustomFieldModuleContent::query()->create([
                            'custom_module_id' => $customFieldModule->custom_module_id,
                            'custom_field_id' => $customFieldModule->custom_field_id,
                            'value' => $customField['value'],
                            'model_id' => $defective->id
                        ]);
                    }
                }
            }
        }

        return $this->message('操作成功');
    }

    public function update(Request $request, Defective $defective)
    {
        $this->authorize('own', $defective);

        $params = $request->all();

        $defective->fill($params);
        $defective->save();

        $customFields = $request->input('custom_fields');

        $customFieldModuleContentIds = [];

        $customModule = CustomModule::query()->where('code', CustomModule::CODE_DEFECTIVE)->first();

        if (isset($customFields) && count($customFields)) {
            foreach ($customFields as $customField) {
                if (isset($customField['custom_field_id']) && $customField['custom_field_id']) {
                    $customFieldModule = CustomFieldModule::query()->where(['custom_field_id' => $customField['custom_field_id'], 'custom_module_id' => $customModule->id])->first();
                    if ($customFieldModule && isset($customField['value']) && $customField['value']) {
                        $customFieldModuleContent = new CustomFieldModuleContent([
                            'custom_module_id' => $customFieldModule->custom_module_id,
                            'custom_field_id' => $customFieldModule->custom_field_id,
                            'model_id' => $defective->id
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
        CustomFieldModuleContent::query()->where(['custom_module_id' => $customModule->id, 'model_id' => $defective->id])
            ->whereNotIn('id', $customFieldModuleContentIds)
            ->delete();

        return $this->message('操作成功');
    }

    public function destroy(Defective $defective)
    {
        $defective->delete();

        return $this->message('操作成功');
    }
}
