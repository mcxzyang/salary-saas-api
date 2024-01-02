<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingTechnologyItem extends Model
{
    use FormatDate;

    protected $fillable = ['working_technology_id', 'working_process_id', 'sort'];
}
