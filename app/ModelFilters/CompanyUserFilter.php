<?php

namespace App\ModelFilters;

use App\Models\CompanyUserDepartment;
use EloquentFilter\ModelFilter;

class CompanyUserFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function companyId($companyId)
    {
        if (is_array($companyId)) {
            return $this->whereIn('company_id', $companyId);
        }
        return $this->where('company_id', $companyId);
    }

    public function name($name)
    {
        return $this->where('name', 'like', sprintf('%%%s%%', $name));
    }

    public function department($departmentId) {
        $companyUserIds = CompanyUserDepartment::query()->where('company_department_id', $departmentId)->pluck('company_user_id');
        return $this->whereIn('id', $companyUserIds);
    }
}
