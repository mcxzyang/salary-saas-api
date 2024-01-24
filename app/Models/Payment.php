<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use App\Traits\RecordUserTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;
    use RecordUserTrait;

    protected $fillable = ['company_id', 'order_id', 'total', 'order_total', 'order_already_payed', 'payment_proof', 'pay_at', 'collection_account_id', 'pay_method', 'pay_desc', 'status', 'created_by', 'updated_by', 'type'];

    protected $casts = [
        'payment_proof' => 'json',
        'pay_at' => 'datetime'
    ];

    public function getAuthGuard()
    {
        return 'client';
    }

    public function collectionAccount()
    {
        return $this->belongsTo(CollectionAccount::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
