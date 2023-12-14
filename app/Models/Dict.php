<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Dict extends Model
{
    use FormatDate;
    use Filterable;

    protected $fillable = ['id', 'name', 'code', 'description', 'is_system'];
}
