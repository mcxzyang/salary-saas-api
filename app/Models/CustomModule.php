<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomModule extends Model
{
    use FormatDate;
    use Filterable;

    public const CODE_CUSTOMER = 'customer';
    public const CODE_DEFECTIVE = 'defective';
    public const CODE_GOODS = 'goods';

    protected $fillable = [
        'name', 'code'
    ];

    public function customFields()
    {
        return $this->belongsToMany(CustomField::class, 'custom_field_modules', 'custom_module_id', 'custom_field_id');
    }
}
