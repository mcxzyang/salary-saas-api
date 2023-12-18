<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEnterItem extends Model
{
    use FormatDate;

    protected $fillable = [
        'id', 'stock_enter_id', 'company_id', 'product_id', 'product_sku_id', 'number'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class);
    }
}
