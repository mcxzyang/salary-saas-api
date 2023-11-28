<?php

namespace App\Models;

use App\Services\ClientOperateLogService;
use App\Traits\BootableTrait;
use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDepartment extends Model
{
    use FormatDate;
    use Filterable;
    use BootableTrait;

    public static $moduleName = '部门';
    public static $primaryName = 'name';

    protected $fillable = ['company_id', 'name', 'supervisor_id', 'status'];


}
