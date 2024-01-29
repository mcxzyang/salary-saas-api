<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkorderTaskReportDefective extends Model
{
    use FormatDate;

    protected $fillable = ['workorder_id', 'workorder_task_report_id', 'workorder_task_id', 'defective_id', 'defective_no', 'defective_name', 'number'];
}
