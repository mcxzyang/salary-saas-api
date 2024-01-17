<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CustomFieldModule;
use App\Models\CustomFieldModuleContent;
use App\Models\CustomModule;
use App\Models\Goods;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Goods::filter($request->all())
            ->where('is_deleted', 0)
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Goods $goods)
    {
        $this->authorize('own', $goods);

        return $this->success(new BaseResource($goods->load(['workingTechnology.workingTechnologyItems.workingProcess'])));
    }

    public function store(Request $request, Goods $goods)
    {
        $this->validate($request, [
            'name' => 'required',
            'unit' => 'required',
            'type' => 'required|in:1,2'
        ]);

        $params = $request->all();

        $user = auth('client')->user();

        $goods->fill(array_merge($params, ['company_id' => $user->company_id, 'status' => 1]));
        $goods->save();

        $customFields = $request->input('custom_fields');
        if (isset($customFields) && count($customFields)) {
            $customModule = CustomModule::query()->where('code', CustomModule::CODE_GOODS)->first();
            foreach ($customFields as $customField) {
                if (isset($customField['custom_field_id']) && $customField['custom_field_id']) {
                    $customFieldModule = CustomFieldModule::query()->where(['custom_field_id' => $customField['custom_field_id'], 'custom_module_id' => $customModule->id])->first();
                    if ($customFieldModule && isset($customField['value']) && $customField['value']) {
                        CustomFieldModuleContent::query()->create([
                            'custom_module_id' => $customFieldModule->custom_module_id,
                            'custom_field_id' => $customFieldModule->custom_field_id,
                            'value' => $customField['value'],
                            'model_id' => $goods->id
                        ]);
                    }
                }
            }
        }

        return $this->message('操作成功');
    }

    public function update(Request $request, Goods $goods)
    {
        $this->authorize('own', $goods);

        $params = $request->all();

        $goods->fill($params);
        $goods->save();

        $customFields = $request->input('custom_fields');

        $customFieldModuleContentIds = [];

        $customModule = CustomModule::query()->where('code', CustomModule::CODE_GOODS)->first();

        if (isset($customFields) && count($customFields)) {
            foreach ($customFields as $customField) {
                if (isset($customField['custom_field_id']) && $customField['custom_field_id']) {
                    $customFieldModule = CustomFieldModule::query()->where(['custom_field_id' => $customField['custom_field_id'], 'custom_module_id' => $customModule->id])->first();
                    if ($customFieldModule && isset($customField['value']) && $customField['value']) {
                        $customFieldModuleContent = new CustomFieldModuleContent([
                            'custom_module_id' => $customFieldModule->custom_module_id,
                            'custom_field_id' => $customFieldModule->custom_field_id,
                            'model_id' => $goods->id
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
        CustomFieldModuleContent::query()->where(['custom_module_id' => $customModule->id, 'model_id' => $goods->id])
            ->whereNotIn('id', $customFieldModuleContentIds)
            ->delete();


        return $this->message('操作成功');
    }

    public function destroy(Goods $goods)
    {
        $this->authorize('own', $goods);

        $goods->is_deleted = 1;
        $goods->save();

        return $this->message('操作成功');
    }
}
