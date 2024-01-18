<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use App\Traits\RecordUserTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class GoodsCategory extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;
    use RecordUserTrait;

    protected $fillable = ['company_id', 'pid', 'name', 'image', 'status', 'created_by', 'updated_by'];

    protected $casts = [];

    public function getAuthGuard()
    {
        return 'client';
    }

    public function parentCategory()
    {
        return $this->belongsTo(GoodsCategory::class, 'pid');
    }

    public function children()
    {
        return $this->hasMany(GoodsCategory::class, 'pid');
    }

    public function allChildren()
    {
        return $this->children()->with(['allChildren']);
    }
}
