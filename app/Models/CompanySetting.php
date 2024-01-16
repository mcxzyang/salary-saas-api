<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use FormatDate;
    use Filterable;

    protected $fillable = ['company_id', 'key', 'value'];
}
