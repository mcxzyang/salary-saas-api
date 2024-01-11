<?php

namespace App\Models;

use App\Traits\BootableTrait;
use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;

//    public static $moduleName = '出库单';
//    public static $primaryName = 'no';

    protected $fillable = ['id', 'company_id', 'no', 'type_id', 'out_at', 'image', 'status', 'description', 'stash_id'];

    public function stockOutItems()
    {
        return $this->hasMany(StockOutItem::class);
    }

    public function type()
    {
        return $this->belongsTo(DictItem::class, 'type_id');
    }

    public function stash()
    {
        return $this->belongsTo(Stash::class);
    }
}
