<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use FormatDate;
    use Filterable;

    public const TYPE_H5 = 'h5';
    public const TYPE_MINI_PROGRAM = 'mini_program';

    public static $typeMap = [
        self::TYPE_H5 => 'H5',
        self::TYPE_MINI_PROGRAM => '微信小程序'
    ];

    protected $fillable = [
        'company_id', 'type', 'mini_program_app_id', 'mini_program_app_secret', 'wechat_pay_app_id',
        'wechat_pay_app_mchid', 'wechat_pay_key', 'status', 'key', 'name'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Client $client) {
            if (is_null($client->key)) {
                $client->key = \Str::uuid();
            }
        });
    }

    protected $appends = [
        'type_name'
    ];

    public function getTypeNameAttribute()
    {
        return self::$typeMap[$this->type];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
