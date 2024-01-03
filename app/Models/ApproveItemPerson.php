<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproveItemPerson extends Model
{
    use FormatDate;

    protected $fillable = ['approve_item_id', 'company_user_id'];

    public function companyUser()
    {
        return $this->belongsTo(CompanyUser::class);
    }
}
