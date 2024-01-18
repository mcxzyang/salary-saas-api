<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproveInstance extends Model
{
    use FormatDate;

    protected $fillable = ['company_id', 'approve_id', 'model_type', 'model_id', 'status'];

    public function approveItemInstances()
    {
        return $this->hasMany(ApproveItemInstance::class)->orderBy('sort');
    }
}
