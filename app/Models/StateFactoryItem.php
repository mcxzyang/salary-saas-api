<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateFactoryItem extends Model
{
    use FormatDate;
    use LogsActivityTrait;

    protected $fillable = [
        'state_factory_id', 'name', 'sort', 'condition_type'
    ];

    public function stateFactoryItemPersons()
    {
        return $this->hasMany(StateFactoryItemPerson::class);
    }
}
