<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workorder extends Model
{
    use FormatDate;
    use LogsActivityTrait;
    use Filterable;

    protected $fillable = ['company_id', 'no', 'goods_id', 'planned_number', 'plan_start_at', 'plan_end_at', 'status', 'is_deleted', 'remark'];

    protected $casts = [
        'planned_number' => 'int',
        'plan_start_at' => 'datetime',
        'plan_end_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Workorder $workorder) {
            if (is_null($workorder->no)) {
                $workorder->no = \Str::random(8);
            }
        });
    }

    public function workorderTasks()
    {
        return $this->hasMany(WorkorderTask::class);
    }
}
