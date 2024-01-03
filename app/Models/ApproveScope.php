<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Model;

class ApproveScope extends Model
{
    use FormatDate;

    protected $fillable = ['approve_id', 'department_id', 'company_user_id', 'scope'];

    public function department()
    {
        return $this->belongsTo(CompanyDepartment::class, 'department_id');
    }

    public function companyUser()
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }
}
