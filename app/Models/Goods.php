<?php

namespace App\Models;

use App\Traits\BootableTrait;
use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    use FormatDate;
    use Filterable;
    use BootableTrait;
    use LogsActivityTrait;

    protected $fillable = ['company_id', 'no', 'name', 'specification', 'unit', 'working_technology_id', 'type', 'max_stock', 'min_stock', 'safe_stock', 'stock_number', 'images', 'status', 'is_deleted'];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Goods $goods) {
            if (is_null($goods->no)) {
                $goods->no = \Str::random(8);
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
