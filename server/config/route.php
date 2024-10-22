<?php

use app\middleware\ActionMiddleware;
use Webman\Route;

Route::post('/test', ['app\admin\controller\TestController', 'test']);
Route::group('/v1', function () {

    /** 后台管理 */
    Route::group('/console', function () {
        /** 不需要授权 */
        Route::group('/auth', function () {
            // 登录
            Route::post('/login', ['app\admin\controller\AccountController', 'login']);
        });
        /** 需要授权 */
        Route::group('', function () {
            // 个人相关
            Route::group('/account', function () {
                // 个人信息
                Route::get('/profile', ['app\admin\controller\AccountController', 'profile']);
                // 退出登录
                Route::post('/logout', ['app\admin\controller\AccountController', 'logout']);
                // 菜单
                Route::post('/menus', ['app\admin\controller\AccountController', 'menus']);
                // 权限
                Route::post('/permissions', ['app\admin\controller\AccountController', 'permissions']);
            });
        });

        /** 系统设置 */
        Route::group('/system', function () {
            // 菜单部分
            Route::group('/menu', function () {
                // 获取菜单列表
                Route::GET('/list', ['app\admin\controller\system\MenuController', 'list']);
                // 新增菜单或权限
                Route::POST('/create', ['app\admin\controller\system\MenuController', 'create'])->middleware(ActionMiddleware::class);
                // 修改菜单或权限
                Route::POST('/update', ['app\admin\controller\system\MenuController', 'update'])->middleware(ActionMiddleware::class);
                // 删除菜单或权限
                Route::get('/delete', ['app\admin\controller\system\MenuController', 'delete'])->middleware(ActionMiddleware::class);
                //获取后端定义的所有权限集
                Route::GET('/permissions', ['app\admin\controller\system\MenuController', 'permissions']);
            });
            // 角色部分
            Route::group('/role', function () {
                // 获取角色列表
                Route::GET('/list', ['app\admin\controller\system\RoleController', 'list']);
                // 新增角色
                Route::POST('/create', ['app\admin\controller\system\RoleController', 'create'])->middleware(ActionMiddleware::class);
                // 修改角色
                Route::POST('/update', ['app\admin\controller\system\RoleController', 'update'])->middleware(ActionMiddleware::class);
                // 删除角色
                Route::GET('/delete', ['app\admin\controller\system\RoleController', 'delete'])->middleware(ActionMiddleware::class);
                // 角色详情
                Route::GET('/detail', ['app\admin\controller\system\RoleController', 'detail']);
            });
            // 系统监控
            Route::group('/monitor', function () {
                // 服务监控
                Route::POST('/server', ['app\admin\controller\system\MonitorController', 'server']);
            });

            Route::group('/log/login', function () {
                // 列表接口
                Route::post('/list', ['app\admin\controller\system\LogLoginController', 'list']);
            });

            /** 请求日志 @date 2024/09/10 20:21 */
            Route::group('/log/request', function () {
                // 列表接口
                Route::post('/list', ['app\admin\controller\system\LogRequestController', 'list']);
                // 详情接口
                Route::post('/detail', ['app\admin\controller\system\LogRequestController', 'detail']);
            });
        });

        /** 公共部分接口 */
        Route::group('/common', function () {
            // 文件上传
            Route::post('/upload', ['app\admin\controller\common\UploadController', 'upload'])->middleware(ActionMiddleware::class);
            // 切片文件上传
            Route::post('/uploadChunk', ['app\admin\controller\common\UploadController', 'chunk'])->middleware(ActionMiddleware::class);
            // 切片合并文件
            Route::post('/chunkMerge', ['app\admin\controller\common\UploadController', 'merge'])->middleware(ActionMiddleware::class);
        });

        /** 文件部分 */
        Route::group('/upload', function () {
            // 文件分组
            Route::group('/group', function () {
                // 获取分组列表
                Route::post('/list', ['app\admin\controller\upload\GroupController', 'list']);
                // 新增分组
                Route::post('/create', ['app\admin\controller\upload\GroupController', 'create']);
                // 修改分组
                Route::post('/update', ['app\admin\controller\upload\GroupController', 'update']);
                // 删除分组
                Route::post('/delete', ['app\admin\controller\upload\GroupController', 'delete']);
                // 下列列表
                Route::post('/select', ['app\admin\controller\upload\GroupController', 'select']);
            });
            /** 文件资源 @date 2024/09/07 17:26 */
            Route::group('/file', function () {
                // 列表接口
                Route::post('/list', ['app\admin\controller\upload\FileController', 'list']);
                // 删除接口
                Route::post('/deleted', ['app\admin\controller\upload\FileController', 'deleted'])->middleware(ActionMiddleware::class);
                // 详情接口
                Route::post('/detail', ['app\admin\controller\upload\FileController', 'detail']);
            });
        });
        /** 代码生成器 **/
        Route::group('/gen', function () {
            // 所有数据表
            Route::get('/table/sheet', ['app\generate\controller\GenerateController', 'dataSheet']);
            // SQL 语句
            Route::post('/table/sql', ['app\generate\controller\GenerateController', 'tableSql']);
            // 数据表详情
            Route::get('/table/sheet/detail', ['app\generate\controller\GenerateController', 'dataSheetDetail']);
            // 开始生成
            Route::post('/generate', ['app\generate\controller\GenerateController', 'generate']);
        });

    });
});
Route::disableDefaultRoute();
