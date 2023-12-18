<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class StockEnterFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function no($no)
    {
        return $this->where('no', 'like', sprintf('%%%s%%', $no));
    }

    public function enterAt($enterAt)
    {
        if (is_array($enterAt)) {
            return $this->whereBetween('enter_at', $enterAt);
        }

        return $this->where('enter_at', '>', $enterAt);
    }
}
