<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class CompanyOption extends Model
{
    use FormatDate;
    use Filterable;

    public const CODE_ORDER_TO_WORKORDER = 'order_auto_to_workorder';

    protected $fillable = ['name', 'code', 'custom_module_id', 'description', 'created_by', 'updated_by'];

    protected $casts = [];

    public function customModule()
    {
        return $this->belongsTo(CustomModule::class);
    }
}
