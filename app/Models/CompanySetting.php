<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\RecordUserTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use FormatDate;
    use Filterable;
    use RecordUserTrait;

    protected $fillable = ['company_id', 'key', 'value', 'created_by', 'updated_by'];

    public function getAuthGuard()
    {
        return 'client';
    }
}
