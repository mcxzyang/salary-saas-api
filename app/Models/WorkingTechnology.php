<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class WorkingTechnology extends Model
{
    use FormatDate;
    use LogsActivityTrait;
    use Filterable;

//    public static $moduleName = '工艺';
//    public static $primaryName = 'name';

    protected $fillable = ['company_id', 'created_by', 'no', 'name', 'status', 'is_deleted'];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (WorkingTechnology $workingTechnology) {
            if (is_null($workingTechnology->no)) {
                $workingTechnology->no = \Str::random(8);
            }
        });
    }

    public function createdUser()
    {
        return $this->belongsTo(CompanyUser::class, 'created_by');
    }

    public function workingTechnologyItems()
    {
        return $this->hasMany(WorkingTechnologyItem::class)->orderBy('sort', 'asc');
    }
}
