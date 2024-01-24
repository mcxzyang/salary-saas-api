<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproveInstance extends Model
{
    use FormatDate;

    protected $fillable = ['company_id', 'approve_id', 'modelable_type', 'modelable_id', 'status', 'if_auto_next'];

    public function approveItemInstances()
    {
        return $this->hasMany(ApproveItemInstance::class)->orderBy('sort');
    }

    public function modelable()
    {
        return $this->morphTo();
    }
}
