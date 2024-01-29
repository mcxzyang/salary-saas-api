<?php

use App\Http\Controllers\Api\Client\ApproveController;
use App\Http\Controllers\Api\Client\ApproveItemPersonInstanceController;
use App\Http\Controllers\Api\Client\AuthController;
use App\Http\Controllers\Api\Client\CategoryController;
use App\Http\Controllers\Api\Client\ClientOperateLogController;
use App\Http\Controllers\Api\Client\CollectionAccountController;
use App\Http\Controllers\Api\Client\CommonController;
use App\Http\Controllers\Api\Client\CompanyDepartmentController;
use App\Http\Controllers\Api\Client\CompanyOptionController;
use App\Http\Controllers\Api\Client\CompanyOptionSetController;
use App\Http\Controllers\Api\Client\CompanyRoleController;
use App\Http\Controllers\Api\Client\CompanySettingController;
use App\Http\Controllers\Api\Client\CompanyUserController;
use App\Http\Controllers\Api\Client\CustomerController;
use App\Http\Controllers\Api\Client\CustomerPassLogController;
use App\Http\Controllers\Api\Client\CustomFieldController;
use App\Http\Controllers\Api\Client\CustomFieldTypeController;
use App\Http\Controllers\Api\Client\DefectiveController;
use App\Http\Controllers\Api\Client\DictController;
use App\Http\Controllers\Api\Client\DictItemController;
use App\Http\Controllers\Api\Client\FollowUpController;
use App\Http\Controllers\Api\Client\GoodsCategoryController;
use App\Http\Controllers\Api\Client\GoodsController;
use App\Http\Controllers\Api\Client\IndustryController;
use App\Http\Controllers\Api\Client\MaterielController;
use App\Http\Controllers\Api\Client\OrderController;
use App\Http\Controllers\Api\Client\PaymentController;
use App\Http\Controllers\Api\Client\PerformanceRuleController;
use App\Http\Controllers\Api\Client\ProductController;
use App\Http\Controllers\Api\Client\StashController;
use App\Http\Controllers\Api\Client\StashTakeStockController;
use App\Http\Controllers\Api\Client\StateFactoryController;
use App\Http\Controllers\Api\Client\StateFactoryItemPersonInstanceController;
use App\Http\Controllers\Api\Client\StockController;
use App\Http\Controllers\Api\Client\StockEnterController;
use App\Http\Controllers\Api\Client\StockOutController;
use App\Http\Controllers\Api\Client\TestController;
use App\Http\Controllers\Api\Client\ToolsController;
use App\Http\Controllers\Api\Client\WorkingProcessController;
use App\Http\Controllers\Api\Client\WorkingTechnologyController;
use App\Http\Controllers\Api\Client\WorkorderController;
use App\Http\Controllers\Api\Client\WorkorderTaskReportController;

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

    // 人员
    Route::resource('/companyUser', CompanyUserController::class);

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

    // 客资跟进
    Route::resource('/followUp', FollowUpController::class);

    // 客资转交
    Route::resource('/customerPassLog', CustomerPassLogController::class);

    // 入库单
    Route::resource('/stockEnter', StockEnterController::class);

    // 出库单
    Route::resource('/stockOut', StockOutController::class);

    // 仓库管理
    Route::resource('/stash', StashController::class);

    // 仓库盘点
    Route::resource('/stashTakeStock', StashTakeStockController::class);

    // 库存查询
    Route::resource('/stock', StockController::class);

    // 工序
    Route::resource('/workingProcess', WorkingProcessController::class);

    // 工艺
    Route::resource('/workingTechnology', WorkingTechnologyController::class);

    // 不良品项
    Route::resource('/defective', DefectiveController::class);

    // 产品
    Route::get('/goods', [GoodsController::class, 'index']);
    Route::get('/goods/{goods}', [GoodsController::class, 'show']);
    Route::post('/goods', [GoodsController::class, 'store']);
    Route::put('/goods/{goods}', [GoodsController::class, 'update']);
    Route::delete('/goods/{goods}', [GoodsController::class, 'destroy']);

    // 产品分类
    Route::resource('/goodsCategory', GoodsCategoryController::class);
    Route::get('/goodsCategory/tree/list', [GoodsCategoryController::class, 'tree']);

    // 自定义审批
    Route::get('/approve/type/list', [ApproveController::class, 'typeList']);
    Route::resource('/approve', ApproveController::class);

    // 工单
    Route::resource('/workorder', WorkorderController::class);

    // 物料
    Route::resource('/materiel', MaterielController::class);

    // 状态机
    Route::resource('/stateFactory', StateFactoryController::class);

    // 订单
    Route::resource('/order', OrderController::class);
    Route::put('/order/{order}/nextStep', [OrderController::class, 'nextStep']);

    // 订单状态审核
    Route::get('/stateFactoryItemPersonInstance', [StateFactoryItemPersonInstanceController::class, 'index']);
    Route::put('/stateFactoryItemPersonInstance/{stateFactoryItemPersonInstance}', [StateFactoryItemPersonInstanceController::class, 'update']);

    // 订单审批
    Route::get('/approveItemPersonInstance', [ApproveItemPersonInstanceController::class, 'index']);
    Route::put('/approveItemPersonInstance/{approveItemPersonInstance}', [ApproveItemPersonInstanceController::class, 'update']);

    // 绩效规则
    Route::resource('/performanceRule', PerformanceRuleController::class);

    // 行业
    Route::get('/industry', [IndustryController::class, 'index']);

    // 设置
    Route::resource('/companySetting', CompanySettingController::class);

    // 用户可设置项 - 总项
    Route::get('/companyOption', [CompanyOptionController::class, 'index']);

    // 用户已设置项
    Route::get('/companyOptionSet', [CompanyOptionSetController::class, 'index']);
    Route::post('/companyOptionSet', [CompanyOptionSetController::class, 'store']);

    // 收款账户
    Route::resource('/collectionAccount', CollectionAccountController::class);

    // 付款
    Route::resource('/payment', PaymentController::class);

    // 报工审核
    Route::get('/workorderTaskReport', [WorkorderTaskReportController::class, 'index']);
    Route::get('/workorderTaskReport/{workorderTaskReport}', [WorkorderTaskReportController::class, 'show']);
    Route::put('/workorderTaskReport/{workorderTaskReport}/audit', [WorkorderTaskReportController::class, 'audit']);
});
