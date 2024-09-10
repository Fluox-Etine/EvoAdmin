<?php
declare (strict_types=1);

namespace app\admin\controller\system;

use app\admin\logic\system\LogLoginLogic as SystemLoginLogLogic;
use support\exception\RespBusinessException;
use support\Request;
use support\Response;

/**
 * 登录日志控制器类
 * Class LogLoginController
 * @package app\admin\controller\sys
 * @date 2024/09/09 21:40
 */
class LogLoginController
{


    /**
     * 获取登录日志列表
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     * @date 2024/09/09 21:40
     */
    public function list(Request $request): Response
    {
        $params = $request->post();
        $list = SystemLoginLogLogic::handleLists($params);
        return renderSuccess($list,'列表获取成功');
    }

}