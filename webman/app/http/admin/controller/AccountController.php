<?php

namespace app\http\admin\controller;

use app\http\admin\service\system\SysAdminService;
use support\exception\RespBusinessException;
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
}