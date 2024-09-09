<?php

namespace app\http\admin\controller;

use app\common\enum\RedisKeyEnum;
use app\http\admin\logic\system\AdminLogic;
use app\http\admin\service\system\SysAdminService;
use app\http\admin\service\system\SysMenuService;
use app\http\admin\service\system\SysPermissionsService;
use support\exception\RespBusinessException;
use support\Redis;
use support\Response;
use Webman\Http\Request;

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
        $token = SysAdminService::handleLogin($request->post());
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
        Redis::del(RedisKeyEnum::ADMIN_TOKEN->value . get_token());
        return renderSuccess('退出成功');
    }

    /**
     * 菜单
     * @return Response
     * @throws RespBusinessException
     */
    public function menus(): Response
    {
        $data = SysMenuService::handleLoginMenuList();
        return renderSuccess($data);
    }

    /**
     * 权限
     * @return Response
     * @throws RespBusinessException
     */
    public function permissions(): Response
    {
        $data = SysPermissionsService::handleLoginPermissionsList();
        return renderSuccess($data);
    }
}