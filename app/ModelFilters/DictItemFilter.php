<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class DictItemFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function dict($dictId)
    {
        return $this->where('dict_id', $dictId);
    }

    public function code($code)
    {
        return $this->whereRelation('dict', 'code', '=', $code);
    }
}
