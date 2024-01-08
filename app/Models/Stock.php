<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use FormatDate;
    use Filterable;

    protected $fillable = [
        'id', 'company_id', 'stash_id', 'goods_id', 'number', 'status'
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
