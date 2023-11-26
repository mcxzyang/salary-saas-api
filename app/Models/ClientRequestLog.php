<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class ClientRequestLog extends Model
{
    use Filterable;
    use FormatDate;

    protected $fillable = [
        'request_url', 'request_method', 'status_code', 'location', 'browser', 'client_ip', 'company_user_id', 'request_body', 'request_header', 'duration', 'response_body'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (ClientRequestLog $clientRequestLog) {
            if ($body = $clientRequestLog->request_body) {
                $formatJson = json_decode($body, true);
                if (isset($formatJson['password']) && $formatJson['password']) {
                    $formatJson['password'] = '******';
                    $clientRequestLog->request_body = json_encode($formatJson);
                }
            }
        });
    }
}
