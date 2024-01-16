<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    use FormatDate;
    use LogsActivityTrait;

//    public static $moduleName = '商品SKU';
//    public static $primaryName = 'sku_name';

    protected $fillable = ['id', 'company_id', 'product_id', 'goods_id', 'sku_number', 'sku_name', 'original_price', 'sales_price', 'unit', 'stock', 'is_deleted'];

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
