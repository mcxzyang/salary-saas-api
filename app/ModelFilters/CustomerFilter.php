<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class CustomerFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function name($name)
    {
        return $this->where('name', 'like', sprintf('%%%s%%', $name));
    }

    public function inChargeCompanyUser($inChargeCompanyUser)
    {
        return $this->where('in_charge_company_user_id', $inChargeCompanyUser);
    }

    public function type($type)
    {
        return $this->where('type', $type);
    }

    public function sex($sex)
    {
        return $this->where('sex', $sex);
    }

    public function phone($phone)
    {
        return $this->where('phone', 'like', sprintf('%%%s%%', $phone));
    }

    public function customerStatus($customerStatus)
    {
        return $this->where('customer_status_id', $customerStatus);
    }

    public function customerType($customerType)
    {
        return $this->where('customer_type_id', $customerType);
    }

    public function level($level)
    {
        return $this->where('level_id', $level);
    }

    public function source($source)
    {
        return $this->where('source_id', $source);
    }

    public function ripeness($ripeness)
    {
        return $this->where('ripeness_id', $ripeness);
    }

    public function industry($industry)
    {
        return $this->where('industry_id', $industry);
    }

    public function commonCustomer($commonCustomer)
    {
        if ($commonCustomer) {
            return $this->whereNull('in_charge_company_user_id');
        }
    }
}
