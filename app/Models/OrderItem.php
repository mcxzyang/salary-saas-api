<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use FormatDate;
    use LogsActivityTrait;

    protected $fillable = [
        'order_id', 'goods_id', 'number', 'price', 'total', 'remark'
    ];

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
