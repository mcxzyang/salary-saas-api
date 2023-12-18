<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class StockOutFilter extends ModelFilter
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

    public function outAt($outAt)
    {
        if (is_array($outAt)) {
            return $this->whereBetween('out_at', $outAt);
        }

        return $this->where('out_at', '>', $outAt);
    }
}
