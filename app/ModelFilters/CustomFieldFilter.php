<?php

namespace App\ModelFilters;

use App\Models\CustomFieldModule;
use EloquentFilter\ModelFilter;

class CustomFieldFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function module($moduleId)
    {
        if (!is_array($moduleId)) {
            $moduleId = [$moduleId];
        }
        $customFields = CustomFieldModule::query()->whereIn('custom_module_id', $moduleId)->pluck('custom_field_id');
        return $this->whereIn('id', $customFields);
    }

    public function type($type)
    {
        return $this->where('type', $type);
    }

    public function name($name)
    {
        return $this->where('name', 'like', sprintf('%%%s%%', $name));
    }
}
