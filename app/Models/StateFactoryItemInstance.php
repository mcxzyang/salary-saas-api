<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateFactoryItemInstance extends Model
{
    use FormatDate;

    protected $fillable = ['state_factory_id', 'state_factory_item_id', 'state_factory_instance_id', 'name', 'sort', 'condition_type', 'status'];
}
