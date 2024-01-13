<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StashTakeStockItem extends Model
{
    use FormatDate;

    protected $fillable = [
        'id', 'company_id', 'stash_take_stock_id', 'goods_id', 'stock_in_stash', 'stock_check', 'remark', 'operate_by', 'status'
    ];

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
