<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use FormatDate;

    protected $fillable = ['purchase_order_id', 'goods_id', 'sku', 'unit', 'order_number', 'notify_number', 'delivery_number', 'amount', 'delivery_at', 'delivery_amount', 'remark'];
}
