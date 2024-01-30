<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class CompanyDepartment extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;

//    public static $moduleName = '部门';
//    public static $primaryName = 'name';

    protected $fillable = ['company_id', 'name', 'supervisor_id', 'status', 'pid'];

    public function children()
    {
        return $this->hasMany(CompanyDepartment::class, 'pid');
    }

    public function allChildren()
    {
        return $this->children()->with(['allChildren', 'parentDepartment']);
    }

    public function parentDepartment()
    {
        return $this->belongsTo(CompanyDepartment::class, 'pid');
    }

    public function supervisor()
    {
        return $this->belongsTo(CompanyUser::class, 'supervisor_id');
    }


}
