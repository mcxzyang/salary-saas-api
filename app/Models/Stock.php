<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use FormatDate;
    use Filterable;

    protected $fillable = [
        'id', 'company_id', 'stash_id', 'product_id', 'product_sku_id', 'number', 'status'
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
