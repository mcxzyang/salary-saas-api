<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class CollectionAccountFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var  array
     */
    public $relations = [];

    public function name($name)
    {
        return $this->where('name', 'like', sprintf('%%%s%%', $name));
    }

    public function accountNo($accountNo)
    {
        return $this->where('account_no', 'like', sprintf('%%%s%%', $accountNo));
    }
}
