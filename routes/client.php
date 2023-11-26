<?php

use App\Http\Controllers\Api\Client\AuthController;
use App\Http\Controllers\Api\Client\CommonController;

// common
Route::get('/common/captcha/img', [CommonController::class, 'captcha']);


// auth
Route::post('/auth/login', [AuthController::class, 'login']);
