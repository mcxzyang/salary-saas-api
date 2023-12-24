<?php

namespace App\Models;

use App\Traits\BootableTrait;
use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stash extends Model
{
    use FormatDate;
    use BootableTrait;
    use Filterable;

    public static $moduleName = '仓库';
    public static $primaryName = 'name';

    protected $fillable = [
        'id', 'company_id', 'no', 'name', 'created_by', 'status', 'remark'
    ];

    public function createdUser()
    {
        return $this->belongsTo(CompanyUser::class, 'created_by');
    }
}
