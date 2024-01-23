<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use App\Traits\RecordUserTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class CustomerPassLog extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;
    use RecordUserTrait;

    protected $fillable = ['company_id', 'customer_id', 'from_user_id', 'to_user_id', 'reason', 'created_by', 'updated_by'];

    protected $casts = [];

    public function getAuthGuard()
    {
        return 'client';
    }

    public function fromUser()
    {
        return $this->belongsTo(CompanyUser::class);
    }

    public function toUser()
    {
        return $this->belongsTo(CompanyUser::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function updateUser()
    {
        return $this->belongsTo(CompanyUser::class);
    }

}
