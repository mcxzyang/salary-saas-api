<?php

use Jenssegers\Agent\Agent;

function getLocation($ip)
{
    $locationResult = geoip($ip)->toArray();
    return sprintf('%s%s%s', $locationResult['country'], $locationResult['state_name'], $locationResult['city']);
}

function getBrowser()
{
    $agent = new Agent();
    return $agent->browser();
}
