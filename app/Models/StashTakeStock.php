<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class StashTakeStock extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;

//    public static $moduleName = '盘点';

    protected $fillable = [
        'id', 'company_id', 'no', 'stash_id', 'take_stock_at', 'created_by', 'remark', 'status'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (StashTakeStock $stashTakeStock) {
            if (is_null($stashTakeStock->no)) {
                $code = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
                $no = 'CKPD'.date('Ymd').$code;
                $stashTakeStock->no = $no;
            }
        });
    }

    public function createdUser()
    {
        return $this->belongsTo(CompanyUser::class, 'created_by');
    }

    public function stash()
    {
        return $this->belongsTo(Stash::class);
    }

    public function stashTakeStockItems()
    {
        return $this->hasMany(StashTakeStockItem::class);
    }
}
