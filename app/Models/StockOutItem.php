<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOutItem extends Model
{
    use FormatDate;

    protected $fillable = ['id', 'company_id', 'stock_out_id', 'goods_id', 'number'];

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
