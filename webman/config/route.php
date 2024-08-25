<?php

use app\middleware\ActionMiddleware;
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

        /** 系统设置 */
        Route::group('/system', function () {
            // 菜单部分
            Route::group('/menu', function () {
                // 获取菜单列表
                Route::GET('/list', ['app\http\admin\controller\system\MenuController', 'list']);
                // 新增菜单或权限
                Route::POST('/create', ['app\http\admin\controller\system\MenuController', 'create'])->middleware(ActionMiddleware::class);
                // 修改菜单或权限
                Route::POST('/update', ['app\http\admin\controller\system\MenuController', 'update'])->middleware(ActionMiddleware::class);
                // 删除菜单或权限
                Route::POST('/delete', ['app\http\admin\controller\system\MenuController', 'delete'])->middleware(ActionMiddleware::class);
                //获取后端定义的所有权限集
                Route::GET('/permissions', ['app\http\admin\controller\system\MenuController', 'permissions']);
            });
            // 角色部分
            Route::group('/role', function () {
                // 获取角色列表
                Route::GET('/list', ['app\http\admin\controller\system\RoleController', 'list']);
                // 新增角色
                Route::POST('/create', ['app\http\admin\controller\system\RoleController', 'create'])->middleware(ActionMiddleware::class);
                // 修改角色
                Route::POST('/update', ['app\http\admin\controller\system\RoleController', 'update'])->middleware(ActionMiddleware::class);
                // 删除角色
                Route::GET('/delete', ['app\http\admin\controller\system\RoleController', 'delete'])->middleware(ActionMiddleware::class);
                // 角色详情
                Route::GET('/detail', ['app\http\admin\controller\system\RoleController', 'detail']);
            });
        });


        /** 代码生成器 **/
        Route::group('/gen', function () {
            // 测试
            Route::get('/test', [app\http\generate\controller\GenerateController::class, 'test']);
            // 所有数据表
            Route::get('/table/sheet', [app\http\generate\controller\GenerateController::class, 'dataSheet']);
            // 数据表详情
            Route::get('/table/sheet/detail', [app\http\generate\controller\GenerateController::class, 'dataSheetDetail']);
            // 开始生成
            Route::post('/generate', [app\http\generate\controller\GenerateController::class, 'generate']);
        });
    });
});
Route::disableDefaultRoute();
