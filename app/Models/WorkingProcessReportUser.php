<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingProcessReportUser extends Model
{
    use FormatDate;

    protected $guarded = ['id'];
}
