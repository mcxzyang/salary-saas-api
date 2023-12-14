<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldModule extends Model
{
    use FormatDate;

    protected $fillable = ['id', 'custom_module_id', 'custom_field_id'];
}
