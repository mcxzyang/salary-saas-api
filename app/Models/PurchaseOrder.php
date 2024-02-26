<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use App\Traits\RecordUserTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;
    use RecordUserTrait;

    protected $fillable = ['company_id', 'purchase_plan_id', 'no', 'vendor_id', 'linkman', 'phone', 'fax', 'delivery_type', 'exp_at', 'audit_user_id', 'audit_at', 'approve_user_id', 'approve_at', 'remark', 'status', 'created_by', 'updated_by'];

    protected $casts = [];

    public function getAuthGuard()
    {
        return 'client';
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function (PurchaseOrder $PurchaseOrder) {
            if (is_null($PurchaseOrder->no)) {
                $code = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
                $no = 'CGD'.date('Ymd').$code;
                $PurchaseOrder->no = $no;
            }
        });
    }

    public function purchaseOrderItem()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
