<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Model;

class PerformanceRuleItem extends Model
{
    use FormatDate;

    protected $fillable = [
        'performance_rule_id',
        'gradient_id',
        'operate_id',
        'conditional_value',
        'price',
        'base_salary',
    ];
}
