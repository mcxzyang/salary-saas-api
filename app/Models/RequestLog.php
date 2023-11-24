<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    protected $fillable = [
        'request_url', 'request_method', 'status_code', 'location', 'browser', 'client_ip', 'user_id', 'request_body', 'request_header', 'duration', 'response_body'
    ];
}
