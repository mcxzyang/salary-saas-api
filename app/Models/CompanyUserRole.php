<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUserRole extends Model
{
    use FormatDate;

    protected $fillable = ['company_user_id', 'company_role_id'];
}
