<?php

namespace App\Models;

use App\Traits\BootableTrait;
use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    use FormatDate;
    use BootableTrait;

    public static $moduleName = '商品SKU';
    public static $primaryName = 'sku_name';

    protected $fillable = ['id', 'company_id', 'product_id', 'sku_number', 'sku_name', 'original_price', 'sales_price', 'unit', 'stock', 'is_deleted'];
}
