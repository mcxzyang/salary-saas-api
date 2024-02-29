<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use FormatDate;
    use LogsActivityTrait;
    use Filterable;

    protected $fillable = [
        'company_id', 'approve_instance_id', 'current_approve_item_instance_id', 'state_factory_instance_id', 'current_state_factory_item_instance_id', 'no', 'customer_id', 'order_at',
        'turnover_at', 'payment_type', 'company_user_id', 'billing_address', 'total', 'received_amount', 'if_invoice', 'remark', 'is_deleted', 'status', 'collection_status', 'after_discount_total'
    ];

    protected $casts = [
        'billing_address' => 'json'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Order $order) {
            if (is_null($order->no)) {
                $code = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
                $no = 'DD'.date('Ymd').$code;
                $order->no = $no;
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function currentStateFactoryItemInstance()
    {
        return $this->belongsTo(StateFactoryItemInstance::class, 'current_state_factory_item_instance_id');
    }

    public function stateFactoryInstance()
    {
        return $this->belongsTo(StateFactoryInstance::class);
    }

    public function currentApproveItemInstance()
    {
        return $this->belongsTo(ApproveItemInstance::class, 'current_approve_item_instance_id');
    }

    public function approveInstance()
    {
        return $this->belongsTo(ApproveInstance::class);
    }

    public function approveItem()
    {
        return $this->belongsTo(ApproveItem::class);
    }

    public function companyUser()
    {
        return $this->belongsTo(CompanyUser::class);
    }
}
