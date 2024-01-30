<?php

// auth
use App\Http\Controllers\Api\Mobile\AuthController;
use App\Http\Controllers\Api\Mobile\DashboardController;
use App\Http\Controllers\Api\Mobile\FeedbackController;
use App\Http\Controllers\Api\Mobile\ToolsController;
use App\Http\Controllers\Api\Mobile\WorkorderController;
use App\Http\Controllers\Api\Mobile\WorkorderTaskController;
use App\Http\Controllers\Api\Mobile\WorkorderTaskReportController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/tools/uploadPic', [ToolsController::class, 'uploadPic']);

Route::group(['middleware' => 'auth:client'], function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/me', [AuthController::class, 'updateUser']);
    Route::put('/auth/updatePassword', [AuthController::class, 'updatePassword']);

    Route::get('/workorderTask', [WorkorderTaskController::class, 'index']);

    Route::get('/workorderTaskReport', [WorkorderTaskReportController::class, 'index']);
    Route::get('/workorderTaskReport/{workorderTaskReport}', [WorkorderTaskReportController::class, 'show']);
    Route::put('/workorderTaskReport/{workorderTaskReport}', [WorkorderTaskReportController::class, 'update']);
    Route::post('/workorderTaskReport', [WorkorderTaskReportController::class, 'store']);

    Route::get('/dashboard/workorderTask', [DashboardController::class, 'workorderTask']);

    Route::get('/workorder/{workorder}', [WorkorderController::class, 'show']);

    Route::post('/feedback', [FeedbackController::class, 'store']);
});
