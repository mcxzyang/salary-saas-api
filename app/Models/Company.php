<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use FormatDate;
    use Filterable;

    protected $fillable = [
        'name', 'link_name', 'link_phone', 'status'
    ];
}
