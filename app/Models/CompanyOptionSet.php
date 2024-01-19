<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyOptionSet extends Model
{
    use FormatDate;

    protected $fillable = ['company_id', 'company_option_code', 'value'];
}
