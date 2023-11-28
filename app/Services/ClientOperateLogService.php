<?php

namespace App\Services;

use App\Jobs\ClientOperateLogJob;

class ClientOperateLogService
{
    public function save($user, $module, $content)
    {
        $ip = request()->getClientIp();
        dispatch(new ClientOperateLogJob([
            'company_id' => optional($user)->company_id,
            'company_user_id' => optional($user)->id,
            'module' => $module,
            'content' => $content,
            'client_ip' => $ip,
            'location' => getLocation($ip),
            'browser' => getBrowser()
        ]));
    }
}
