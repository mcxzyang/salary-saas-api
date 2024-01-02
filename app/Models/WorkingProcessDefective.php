<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingProcessDefective extends Model
{
    use FormatDate;

    protected $fillable = ['working_process_id', 'defective_id'];
}
