<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    use FormatDate;
    use LogsActivityTrait;
    use Filterable;

    protected $fillable = [
        'company_id', 'no', 'name', 'goods_id', 'spec', 'unit', 'stash_id', 'images', 'is_deleted', 'status'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Materiel $materiel) {
            if (is_null($materiel->no)) {
                $materiel->no = \Str::random(8);
            }
        });
    }

    protected $casts = [
        'images' => 'json'
    ];

    public function stash()
    {
        return $this->belongsTo(Stash::class);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
