<?php

use Webman\Route;

// 代码生成器
Route::group('/gen', function () {
    Route::get('/test', [app\http\generate\controller\GenerateController::class, 'test']);

    // 所有数据表
    Route::get('/table/sheet', [app\http\generate\controller\GenerateController::class, 'dataSheet']);
});
Route::disableDefaultRoute();
