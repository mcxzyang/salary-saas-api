<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use App\Traits\RecordUserTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;
    use RecordUserTrait;

    protected $fillable = ['company_id', 'code', 'name', 'short_name', 'level', 'ripeness', 'area', 'address', 'linkman_info', 'bank_info', 'tax_no', 'remark', 'status', 'created_by', 'updated_by'];

    protected $casts = [
        'linkman_info' => 'json',
        'bank_info' => 'json'
    ];

    public function getAuthGuard()
    {
        return 'client';
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Vendor $vendor) {
            if (is_null($vendor->code)) {
                $code = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
                $code = 'GYS'.date('Ymd').$code;
                $vendor->code = $code;
            }
        });
    }
}
