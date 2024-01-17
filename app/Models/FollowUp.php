<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use App\Traits\RecordUserTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;
    use RecordUserTrait;

    protected $fillable = ['company_id', 'customer_id', 'type_id', 'content', 'next_follow_up_at', 'images', 'created_by', 'updated_by'];

    protected $casts = [
        'images' => 'json'
    ];

    public function getAuthGuard()
    {
        return 'client';
    }

    public function type()
    {
        return $this->belongsTo(DictItem::class, 'type_id');
    }
}
