<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use App\Traits\RecordUserTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class PurchasePlan extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;
    use RecordUserTrait;

    protected $fillable = ['company_id', 'no', 'plan_at', 'audit_user_id', 'audit_at', 'approve_user_id', 'approve_at', 'status', 'created_by', 'updated_by'];

    protected $casts = [];

    public function getAuthGuard()
    {
        return 'client';
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function (PurchasePlan $purchasePlan) {
            if (is_null($purchasePlan->no)) {
                $code = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
                $no = 'CGJH'.date('Ymd').$code;
                $purchasePlan->no = $no;
            }
        });
    }

    public function purchasePlanItems()
    {
        return $this->hasMany(PurchasePlanItem::class);
    }
}
