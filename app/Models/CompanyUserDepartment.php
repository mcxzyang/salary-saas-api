<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUserDepartment extends Model
{
    use FormatDate;

    protected $fillable = ['company_user_id', 'company_department_id'];
}
