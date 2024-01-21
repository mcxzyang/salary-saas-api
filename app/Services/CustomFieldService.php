<?php

namespace App\Services;

use App\Models\CustomFieldModule;
use App\Models\CustomFieldModuleContent;
use App\Models\CustomModule;

class CustomFieldService
{
    public function createOrUpdate($fields, $customModuleCode, $modelId)
    {
        $customModule = CustomModule::query()->where('code', $customModuleCode)->first();
        if ($customModule && $fields && count($fields)) {
            $customFieldModuleContentIds = [];
            foreach ($fields as $customField) {
                if (isset($customField['custom_field_id']) && $customField['custom_field_id']) {
                    $customFieldModule = CustomFieldModule::query()->where(['custom_field_id' => $customField['custom_field_id'], 'custom_module_id' => $customModule->id])->first();
                    if ($customFieldModule && isset($customField['value']) && $customField['value']) {
                        $customFieldModuleContent = new CustomFieldModuleContent([
                            'custom_module_id' => $customFieldModule->custom_module_id,
                            'custom_field_id' => $customFieldModule->custom_field_id,
                            'model_id' => $modelId
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
            CustomFieldModuleContent::query()->where(['custom_module_id' => $customModule->id, 'model_id' => $modelId])
                ->whereNotIn('id', $customFieldModuleContentIds)
                ->delete();
        }
    }
}
