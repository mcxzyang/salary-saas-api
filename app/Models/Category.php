<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;

//    public static $moduleName = '商品分类';
//    public static $primaryName = 'name';

    protected $fillable = ['id', 'company_id', 'pid', 'name', 'image', 'status'];

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'pid');
    }
}
