<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproveItemPersonInstance extends Model
{
    use FormatDate;

    protected $fillable = ['approve_item_id', 'approve_item_person_id', 'approve_item_instance_id', 'company_id', 'company_user_id', 'result', 'reject_reason', 'remark', 'approve_at'];

    public function approveItemInstance()
    {
        return $this->belongsTo(ApproveItemInstance::class);
    }
}
