<?php

use App\Http\Controllers\Api\Client\AuthController;
use App\Http\Controllers\Api\Client\CommonController;
use App\Http\Controllers\Api\Client\CompanyDepartmentController;
use App\Http\Controllers\Api\Client\CompanyRoleController;

// common
Route::get('/common/captcha/img', [CommonController::class, 'captcha']);


// auth
Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:client'], function () {
    Route::resource('/companyRole', CompanyRoleController::class);

    Route::resource('/companyDepartment', CompanyDepartmentController::class);

    Route::get('/auth/menu', [AuthController::class, 'menu']);
});
