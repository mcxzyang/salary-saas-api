<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class StockFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function stash($stash)
    {
        return $this->where('stash_id', $stash);
    }
}
