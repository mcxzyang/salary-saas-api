<?php
/**
 * This file is part of the ${PROJECT_NAME}
 *
 * (c) cherrybeal <mcxzyang@gmail.com>
 *
 * This source file is subject to the MIT license is bundled
 * with the source code in the file LICENSE
 */

namespace App\Http\Middleware;

use App\Jobs\ClientRequestLogJob;
use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class RequestLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $environment = app()->environment();

        // production
        if ($environment === 'production' || $environment === 'local') {
            // 计算请求处理时间（毫秒）
            $endTime = microtime(true);
            $processingTime = round(($endTime - LARAVEL_START) * 1000, 2); // 转换为毫秒，并保留两位小数
            $agent = new Agent();

            $ip = $request->getClientIp();
            $locationResult = geoip($ip)->toArray();
            dispatch(new ClientRequestLogJob([
                'request_url' => $request->fullUrl(),
                'request_method' => $request->method(),
                'client_ip' => $ip,
                'request_body' => json_encode($request->all()),
                'response_body' => $response->getContent(),
                'company_user_id' => auth('client')->user() ? auth('client')->user()->id : 0,
                'status_code' => $response->getStatusCode(),
                'location' => sprintf('%s%s%s', $locationResult['country'], $locationResult['state_name'], $locationResult['city']),
                'browser' => $agent->browser(),
                'request_header' => json_encode($request->headers->all()),
                'duration' => $processingTime
            ]));
        }
    }
}
