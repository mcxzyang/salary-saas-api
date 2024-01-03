<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproveItem extends Model
{
    use FormatDate;

    protected $fillable = ['approve_id', 'is_allow_edit', 'status'];

    public function approveItemPersons()
    {
        return $this->hasMany(ApproveItemPerson::class);
    }
}
