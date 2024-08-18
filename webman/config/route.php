<?php

use Webman\Route;

Route::get('/test',[app\http\generate\controller\TestController::class, 'test']);
// 代码生成器
Route::group('/gen', function () {
    Route::get('/test', [app\http\generate\controller\GenerateController::class, 'test']);

    // 所有数据表
    Route::get('/table/sheet', [app\http\generate\controller\GenerateController::class, 'dataSheet']);
    // 数据表详情
    Route::get('/table/sheet/detail', [app\http\generate\controller\GenerateController::class, 'dataSheetDetail']);
});
Route::disableDefaultRoute();
