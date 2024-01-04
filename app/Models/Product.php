<?php

namespace App\Models;

use App\Traits\BootableTrait;
use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;

//    public static $moduleName = '商品';
//    public static $primaryName = 'title';

    protected $fillable = ['id', 'company_id', 'title', 'image', 'carousel_images', 'product_number', 'category_id', 'sales_number', 'view_number', 'content', 'sort', 'status'];

    protected $casts = [
        'carousel_images' => 'json',
        'content' => 'json'
    ];

    public function productSkus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function allProductSkus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
