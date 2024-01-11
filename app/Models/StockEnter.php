<?php

namespace App\Models;

use App\Traits\BootableTrait;
use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEnter extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;

//    public static $moduleName = '入库单';
//    public static $primaryName = 'no';

    protected $fillable = ['id', 'company_id', 'no', 'type_id', 'enter_at', 'description', 'status', 'stash_id'];

    public function stockEnterItems()
    {
        return $this->hasMany(StockEnterItem::class);
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
