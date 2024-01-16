<?php

use Jenssegers\Agent\Agent;

/**
 * @throws \Exception
 */
function getLocation($ip)
{
    $ip2region = new \Ip2Region();
    return $ip2region->simple($ip);
//    $locationResult = geoip($ip)->toArray();
//    return sprintf('%s%s%s', $locationResult['country'], $locationResult['state_name'], $locationResult['city']);
}

function getBrowser()
{
    $agent = new Agent();
    return $agent->browser();
}

function camel($string)
{
    return \Illuminate\Support\Str::camel($string);
}
