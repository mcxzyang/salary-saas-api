<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateFactoryInstance extends Model
{
    use FormatDate;

    protected $fillable = ['company_id', 'state_factory_id', 'modelable_type', 'modelable_id', 'status'];

    public function stateFactoryItemInstances()
    {
        return $this->hasMany(StateFactoryItemInstance::class)->orderBy('sort');
    }

    public function modelable()
    {
        return $this->morphTo();
    }
}
