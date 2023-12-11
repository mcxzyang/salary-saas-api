<?php

use App\Http\Controllers\Api\Client\AuthController;
use App\Http\Controllers\Api\Client\CategoryController;
use App\Http\Controllers\Api\Client\ClientOperateLogController;
use App\Http\Controllers\Api\Client\CommonController;
use App\Http\Controllers\Api\Client\CompanyDepartmentController;
use App\Http\Controllers\Api\Client\CompanyRoleController;
use App\Http\Controllers\Api\Client\ProductController;
use App\Http\Controllers\Api\Client\ToolsController;

// common
Route::get('/common/captcha/img', [CommonController::class, 'captcha']);

// auth
Route::post('/auth/login', [AuthController::class, 'login']);

Route::post('/tools/uploadPic', [ToolsController::class, 'uploadPic']);

Route::group(['middleware' => 'auth:client'], function () {
    // 角色
    Route::resource('/companyRole', CompanyRoleController::class);

    // 部门
    Route::resource('/companyDepartment', CompanyDepartmentController::class);

    // 菜单
    Route::get('/auth/menu', [AuthController::class, 'menu']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // 商品分类
    Route::resource('/category', CategoryController::class);

    // 操作日志
    Route::get('/clientOperateLog', [ClientOperateLogController::class, 'index']);

    // 商品
    Route::resource('/product', ProductController::class);
});
