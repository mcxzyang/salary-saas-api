<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePlanItem extends Model
{
    use FormatDate;

    protected $fillable = ['purchase_plan_id', 'goods_id', 'unit', 'number', 'order_number', 'delivery_number', 'delivery_at', 'remark'];
}
