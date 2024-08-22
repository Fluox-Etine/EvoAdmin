<?php

use Webman\Route;

Route::group('/v1', function () {

    /** 后台管理 */
    Route::group('/console', function () {
        /** 不需要授权 */
        Route::group('/auth', function () {
            // 登录
            Route::post('/login', [app\http\admin\controller\AccountController::class, 'login']);
        });
        /** 需要授权 */
        Route::group('', function () {
            // 个人相关
            Route::group('/account', function () {
                // 个人信息
                Route::get('/profile', [app\http\admin\controller\AccountController::class, 'profile']);
                // 退出登录
                Route::post('/logout', [app\http\admin\controller\AccountController::class, 'logout']);
                // 菜单
                Route::post('/menus', [app\http\admin\controller\AccountController::class, 'menus']);
                // 权限
                Route::post('/permissions', [app\http\admin\controller\AccountController::class, 'permissions']);
            });
        });
    });
    /** 代码生成器 **/
    Route::group('/gen', function () {
        Route::get('/test', [app\http\generate\controller\GenerateController::class, 'test']);

        // 所有数据表
        Route::get('/table/sheet', [app\http\generate\controller\GenerateController::class, 'dataSheet']);
        // 数据表详情
        Route::get('/table/sheet/detail', [app\http\generate\controller\GenerateController::class, 'dataSheetDetail']);
    });
});
Route::disableDefaultRoute();
