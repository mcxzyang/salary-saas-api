<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class WorkorderTaskFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function createdAt($createdAt)
    {
        if (is_array($createdAt)) {
            return $this->whereBetween('created_at', $createdAt);
        }
        return $this->where('created_at', '>', $createdAt);
    }
}
