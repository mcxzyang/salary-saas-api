<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkorderTaskReport extends Model
{
    use FormatDate;
    use LogsActivityTrait;
    use Filterable;

    protected $fillable = [
        'workorder_id', 'workorder_task_id', 'created_by', 'product_person_id', 'report_call_number', 'good_product_number', 'ungood_product_number', 'start_at', 'end_at',
        'approve_result', 'approve_company_user_id', 'defectives', 'commission_amount'
    ];

    protected $casts = [
        // 'product_person_ids' => 'json'
        'defectives' => 'json'
    ];

    public function workorder()
    {
        return $this->belongsTo(Workorder::class);
    }

    public function workorderTask()
    {
        return $this->belongsTo(WorkorderTask::class);
    }
}
