<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use FormatDate;
    use Filterable;

    protected $fillable = [
        'name', 'link_name', 'link_phone', 'status', 'no'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Company $company) {
            if (is_null($company->no)) {
                $code = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
                $no = 'KH'.date('Ymd').$code;
                $company->no = $no;
            }
        });
    }
}
