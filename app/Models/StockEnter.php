<?php

namespace App\Models;

use App\Traits\BootableTrait;
use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEnter extends Model
{
    use FormatDate;
    use Filterable;
    use BootableTrait;

    public static $moduleName = '入库单';
    public static $primaryName = 'no';

    protected $fillable = ['id', 'company_id', 'no', 'type_id', 'enter_at', 'description', 'status', 'stash_id'];

    public function stockEnterItems()
    {
        return $this->hasMany(StockEnterItem::class);
    }

    public function type()
    {
        return $this->belongsTo(DictItem::class, 'type_id');
    }
}
