<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class DictFilter extends ModelFilter
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

    public function code($code)
    {
        return $this->where('code', $code);
    }
}
