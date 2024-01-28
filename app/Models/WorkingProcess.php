<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class WorkingProcess extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;

//    public static $moduleName = '工序';
//    public static $primaryName = 'name';

    protected $fillable = [
        'company_id', 'no', 'created_by', 'name', 'status', 'report_working_permission', 'report_working_rate', 'approve_company_user_id'
    ];

    protected $casts = [
        'report_working_permission' => 'json'
    ];

    public function createdUser()
    {
        return $this->belongsTo(CompanyUser::class, 'created_by');
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function (WorkingProcess $workingProcess) {
            if (is_null($workingProcess->no)) {
                $workingProcess->no = \Str::random(8);
            }
        });
    }

    public function defectives()
    {
        return $this->belongsToMany(Defective::class, 'working_process_defectives', 'working_process_id', 'defective_id');
    }

    public function approveUser()
    {
        return $this->belongsTo(CompanyUser::class, 'approve_company_user_id');
    }
}
