<?php

namespace App\Models;

use App\Traits\BootableTrait;
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
        'company_id', 'no', 'created_by', 'name', 'status'
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
}
