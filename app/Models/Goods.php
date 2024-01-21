<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Goods extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;

    protected $fillable = ['company_id', 'no', 'name', 'specification', 'unit', 'working_technology_id', 'type', 'max_stock', 'min_stock', 'safe_stock', 'stock_number', 'images', 'status', 'is_deleted', 'price', 'stash_id', 'goods_category_id'];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Goods $goods) {
            if (is_null($goods->no)) {
                $code = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
                $no = 'CP'.date('Ymd').$code;
                $goods->no = $no;
            }
        });
    }

    protected $casts = [
        'images' => 'json'
    ];

    public function workingTechnology()
    {
        return $this->belongsTo(WorkingTechnology::class)->withDefault();
    }

    public function stash(): BelongsTo
    {
        return $this->belongsTo(Stash::class);
    }

    public function goodsCategory()
    {
        return $this->belongsTo(GoodsCategory::class);
    }
}
