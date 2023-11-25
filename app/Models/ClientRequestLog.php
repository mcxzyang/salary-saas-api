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
        'request_url', 'request_method', 'status_code', 'location', 'browser', 'client_ip', 'user_id', 'request_body', 'request_header', 'duration', 'response_body'
    ];
}
