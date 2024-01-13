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

    protected static function boot()
    {
        parent::boot();

        self::creating(function (StockEnter $stockEnter) {
            if (is_null($stockEnter->no)) {
                $code = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
                $no = 'RKD'.date('Ymd').$code;
                $stockEnter->no = $no;
            }
        });
    }

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
