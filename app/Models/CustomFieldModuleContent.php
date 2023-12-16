<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldModuleContent extends Model
{
    use FormatDate;

    protected $fillable = ['id', 'custom_module_id', 'custom_field_id', 'value', 'model_id'];

    protected $casts = [
        'value' => 'json'
    ];

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }

    public function customModule()
    {
        return $this->belongsTo(CustomModule::class);
    }
}
