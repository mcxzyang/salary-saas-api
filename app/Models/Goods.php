<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;

    protected $fillable = ['company_id', 'no', 'name', 'specification', 'unit', 'working_technology_id', 'type', 'max_stock', 'min_stock', 'safe_stock', 'stock_number', 'images', 'status', 'is_deleted', 'price'];

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
        return $this->belongsTo(WorkingTechnology::class);
    }
}
