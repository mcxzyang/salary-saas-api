<?php

use App\Http\Controllers\Api\Client\AuthController;
use App\Http\Controllers\Api\Client\CategoryController;
use App\Http\Controllers\Api\Client\ClientOperateLogController;
use App\Http\Controllers\Api\Client\CommonController;
use App\Http\Controllers\Api\Client\CompanyDepartmentController;
use App\Http\Controllers\Api\Client\CompanyRoleController;
use App\Http\Controllers\Api\Client\CustomerController;
use App\Http\Controllers\Api\Client\CustomFieldController;
use App\Http\Controllers\Api\Client\CustomFieldTypeController;
use App\Http\Controllers\Api\Client\DictController;
use App\Http\Controllers\Api\Client\DictItemController;
use App\Http\Controllers\Api\Client\ProductController;
use App\Http\Controllers\Api\Client\StockEnterController;
use App\Http\Controllers\Api\Client\StockOutController;
use App\Http\Controllers\Api\Client\TestController;
use App\Http\Controllers\Api\Client\ToolsController;

// common
Route::get('/common/captcha/img', [CommonController::class, 'captcha']);

// auth
Route::post('/auth/login', [AuthController::class, 'login']);

Route::post('/tools/uploadPic', [ToolsController::class, 'uploadPic']);

//Route::get('/test', [TestController::class, 'index']);
//Route::get('/setStock', [TestController::class, 'setStock']);
//Route::get('/decr', [TestController::class, 'decr']);

// 自定义字段类型
Route::resource('/customFieldType', CustomFieldTypeController::class);

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

    // 自定义字段
    Route::resource('/customField', CustomFieldController::class);
    Route::get('/customField/type/list', [CustomFieldController::class, 'typeList']);
    Route::get('/customField/module/list', [CustomFieldController::class, 'moduleList']);

    // 字典管理
    Route::get('/dict', [DictController::class, 'index']);
    Route::resource('/dictItem', DictItemController::class);

    // 客资管理
    Route::resource('/customer', CustomerController::class);

    // 入库单
    Route::resource('/stockEnter', StockEnterController::class);

    // 出库单
    Route::resource('/stockOut', StockOutController::class);
});
