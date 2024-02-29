<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproveItemInstance extends Model
{
    use FormatDate;

    protected $fillable = ['approve_id', 'approve_item_id', 'approve_instance_id', 'sort', 'condition_type', 'status'];

    public function approveInstance()
    {
        return $this->belongsTo(ApproveInstance::class);
    }

    public function approveItem()
    {
        return $this->belongsTo(ApproveItem::class);
    }
}
