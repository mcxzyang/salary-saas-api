<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOperateLog extends Model
{
    use FormatDate;

    protected $fillable = ['company_id', 'company_user_id', 'module', 'content', 'client_ip', 'location', 'browser'];

    public function companyUser()
    {
        return $this->belongsTo(CompanyUser::class);
    }
}
