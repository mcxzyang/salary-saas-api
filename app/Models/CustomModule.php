<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomModule extends Model
{
    use FormatDate;

    public const CODE_CUSTOMER = 'customer';
    public const CODE_DEFECTIVE = 'defective';

    protected $fillable = [
        'name', 'code'
    ];

    public function customFields()
    {
        return $this->belongsToMany(CustomField::class, 'custom_field_modules', 'custom_module_id', 'custom_field_id');
    }
}
