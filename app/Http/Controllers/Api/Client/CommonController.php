<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    public function captcha()
    {
        return $this->success(app('captcha')->create('flat', true));
    }
}
