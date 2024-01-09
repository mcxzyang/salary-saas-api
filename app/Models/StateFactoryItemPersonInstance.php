<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateFactoryItemPersonInstance extends Model
{
    use FormatDate;

    protected $fillable = ['state_factory_item_id', 'state_factory_item_person_id', 'state_factory_item_instance_id', 'company_user_id', 'company_id', 'result', 'reject_reason', 'remark', 'approve_at'];
}
