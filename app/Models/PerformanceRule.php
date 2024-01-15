<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformanceRule extends Model
{
    use FormatDate;
    use LogsActivityTrait;
    use Filterable;

    protected $fillable = [
        'name',
        'no',
        'type',
        'goods_id',
        'working_process_id',
        'salary_type',
        'basic_salary',
        'price',
        'unit',
        'status',
        'company_id'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (PerformanceRule $performanceRule) {
            if (is_null($performanceRule->no)) {
                $code = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
                $no = 'JXGZ'.date('Ymd').$code;
                $performanceRule->no = $no;
            }
        });
    }

    public function performanceRuleItems()
    {
        return $this->hasMany(PerformanceRuleItem::class, 'performance_rule_id');
    }
}
