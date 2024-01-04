<?php

namespace App\Models;

use App\Traits\BootableTrait;
use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Defective extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;

//    public static $moduleName = '不良品项';
//    public static $primaryName = 'name';

    protected $fillable = ['company_id', 'no', 'name', 'images', 'attachments', 'status'];

    protected $casts = [
        'images' => 'json',
        'attachments' => 'json'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Defective $defective) {
            if (is_null($defective->no)) {
                $defective->no = \Str::random(8);
            }
        });
    }
}
