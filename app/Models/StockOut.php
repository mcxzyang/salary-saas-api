<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;

//    public static $moduleName = '出库单';
//    public static $primaryName = 'no';

    protected $fillable = ['id', 'company_id', 'no', 'type_id', 'out_at', 'image', 'status', 'description', 'stash_id'];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (StockOut $stockOut) {
            if (is_null($stockOut->no)) {
                $code = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
                $no = 'CKD'.date('Ymd').$code;
                $stockOut->no = $no;
            }
        });
    }

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
