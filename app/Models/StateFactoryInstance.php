<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateFactoryInstance extends Model
{
    use FormatDate;

    protected $fillable = ['company_id', 'state_factory_id', 'model_type', 'model_id'];

    public function stateFactoryItemInstances()
    {
        return $this->hasMany(StateFactoryItemInstance::class)->orderBy('sort');
    }
}
