<?php


use App\Http\Controllers\Api\Admin\AdminMenuController;
use App\Http\Controllers\Api\Admin\AdminRoleController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\ClientController;
use App\Http\Controllers\Api\Admin\ClientRequestLogController;
use App\Http\Controllers\Api\Admin\CommonController;
use App\Http\Controllers\Api\Admin\CompanyController;
use App\Http\Controllers\Api\Admin\CompanyMenuController;
use App\Http\Controllers\Api\Admin\CompanyOptionController;
use App\Http\Controllers\Api\Admin\CompanyUserController;
use App\Http\Controllers\Api\Admin\CustomModuleController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\DictController;
use App\Http\Controllers\Api\Admin\DictItemController;
use App\Http\Controllers\Api\Admin\ToolsController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::post('/tools/uploadPic', [ToolsController::class, 'uploadPic']);

Route::get('/common/captcha/img', [CommonController::class, 'captcha']);

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::patch('/auth/updatePassword', [AuthController::class, 'updatePassword']);

    // Dashboard 统计
    Route::get('/dashboard/contentData', [DashboardController::class, 'contentData']);
    Route::get('/dashboard/popular', [DashboardController::class, 'popular']);
    Route::get('/dashboard/announcement', [DashboardController::class, 'announcement']);

    // 权限管理
    Route::get('/adminUser/list/export', [AdminUserController::class, 'export']);

    Route::resource('/adminMenu', AdminMenuController::class);
    Route::get('/adminMenu/list/tree', [AdminMenuController::class, 'tree']);
    Route::get('/adminMenu/list/route', [AdminMenuController::class, 'route']);

    Route::resource('/adminUser', AdminUserController::class);
    Route::resource('/adminRole', AdminRoleController::class);

    Route::resource('/clientRequestLog', ClientRequestLogController::class);

    Route::resource('/company', CompanyController::class);

    Route::resource('/client', ClientController::class);
    Route::get('/client/type/list', [ClientController::class, 'typeList']);

    Route::resource('/companyUser', CompanyUserController::class);

    Route::resource('/companyMenu', CompanyMenuController::class);
    Route::get('/companyMenu/list/tree', [CompanyMenuController::class, 'tree']);

    Route::resource('/dict', DictController::class);
    Route::resource('/dictItem', DictItemController::class);

    Route::resource('/customModule', CustomModuleController::class);

    Route::resource('/companyOption', CompanyOptionController::class);
});
