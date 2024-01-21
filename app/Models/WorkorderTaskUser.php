<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkorderTaskUser extends Model
{
    use FormatDate;

    protected $fillable = ['workorder_task_id', 'company_user_id'];
}
