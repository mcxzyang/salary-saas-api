<?php

// auth
use App\Http\Controllers\Api\Mobile\AuthController;
use App\Http\Controllers\Api\Mobile\WorkorderTaskController;
use App\Http\Controllers\Api\Mobile\WorkorderTaskReportController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:client'], function () {
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::get('/workorderTask', [WorkorderTaskController::class, 'index']);

    Route::get('/workorderTaskReport', [WorkorderTaskReportController::class, 'index']);
    Route::get('/workorderTaskReport/{workorderTaskReport}', [WorkorderTaskReportController::class, 'show']);
    Route::post('/workorderTaskReport', [WorkorderTaskReportController::class, 'store']);
});
