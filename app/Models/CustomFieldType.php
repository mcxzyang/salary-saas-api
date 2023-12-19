<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldType extends Model
{
    use FormatDate;

    protected $fillable = ['id', 'name', 'type', 'tag', 'tag_icon', 'options', 'status'];

    protected $casts = [
        'options' => 'json'
    ];
}
