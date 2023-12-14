<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictItem extends Model
{
    use FormatDate;
    use Filterable;

    protected $fillable = ['id', 'dict_id', 'value', 'sort', 'description', 'is_system', 'company_id'];
}
