<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkorderTask extends Model
{
    use FormatDate;

    protected $fillable = ['workorder_id', 'name', 'no'];
}
