<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Approve extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;

    public const TYPE_CUSTOM_PRODUCT = 1;
    public const TYPE_ORDER_APPROVE = 2;

    public static $typeMap = [
        self::TYPE_CUSTOM_PRODUCT => '定制产品',
        self::TYPE_ORDER_APPROVE => '订单审批'
    ];

    protected $fillable = ['company_id', 'type', 'name', 'scope', 'is_deleted', 'status'];

    public function approveScopes()
    {
        return $this->hasMany(ApproveScope::class);
    }

    public function approveItems()
    {
        return $this->hasMany(ApproveItem::class);
    }
}
