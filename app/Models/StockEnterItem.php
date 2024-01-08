<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEnterItem extends Model
{
    use FormatDate;

    protected $fillable = [
        'id', 'stock_enter_id', 'company_id', 'goods_id', 'number'
    ];

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
