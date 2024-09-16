<?php

namespace app\admin\controller;

use app\admin\logic\system\AdminLogic;
use app\admin\service\system\AdminService;
use app\admin\service\system\MenuService;
use app\admin\service\system\PermissionsService;
use app\common\enum\RedisKeyEnum;
use support\Cache;
use support\exception\RespBusinessException;
use support\Redis;
use support\Response;
use support\Request;

class AccountController
{

    /**
     * 登录
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     */
    public function login(Request $request): Response
    {
        $token = AdminService::handleLogin($request->post());
        return renderSuccess(['token' => $token], '登录成功');
    }

    /**
     * 个人信息
     * @return Response
     * @throws RespBusinessException
     */
    public function profile(): Response
    {
        $data = AdminLogic::handleProfile();
        return renderSuccess($data);
    }

    /**
     * 退出登录
     * @return Response
     */
    public function logout(): Response
    {
        Cache::delete(RedisKeyEnum::ADMIN_TOKEN->value . get_token());
        return renderSuccess('退出成功');
    }

    /**
     * 菜单
     * @return Response
     * @throws RespBusinessException
     */
    public function menus(): Response
    {
        $data = MenuService::handleLoginMenuList();
        return renderSuccess($data);
    }

    /**
     * 权限
     * @return Response
     * @throws RespBusinessException
     */
    public function permissions(): Response
    {
        $data = PermissionsService::handleLoginPermissionsList();
        return renderSuccess($data);
    }
}