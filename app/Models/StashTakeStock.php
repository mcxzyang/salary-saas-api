<?php

namespace App\Models;

use App\Traits\BootableTrait;
use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StashTakeStock extends Model
{
    use FormatDate;
    use Filterable;
    use BootableTrait;

    public static $moduleName = '盘点';

    protected $fillable = [
        'id', 'company_id', 'no', 'stash_id', 'take_stock_at', 'created_by', 'remark', 'status'
    ];

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
