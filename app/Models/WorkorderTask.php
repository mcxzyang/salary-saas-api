<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkorderTask extends Model
{
    use FormatDate;

    protected $fillable = ['workorder_id', 'name', 'no', 'working_process_id', 'report_working_rate', 'plan_start_at', 'plan_end_at', 'plan_number', 'actual_start_at', 'actual_end_at',
        'good_score_number', 'ungood_score_number', 'working_process_charge_user_id', 'report_working_charge_user_id'];

    protected $casts = [
        'plan_start_at' => 'datetime',
        'plan_end_at' => 'datetime',
        'actual_start_at' => 'datetime',
        'actual_end_at' => 'datetime'
    ];

    public function workingProcess()
    {
        return $this->belongsTo(WorkingProcess::class);
    }

    public function workingProcessChargeUser()
    {
        return $this->belongsTo(CompanyUser::class, 'working_process_charge_user_id');
    }

    public function reportWorkingChargeUser()
    {
        return $this->belongsTo(CompanyUser::class, 'report_working_charge_user_id');
    }
}
