<?php
declare (strict_types=1);

namespace app\http\admin\controller\system;

use support\Request;
use support\Response;
use support\exception\RespBusinessException;
use app\http\admin\logic\system\LogLoginLogic as SystemLoginLogLogic;

/**
 * 登录日志控制器类
 * Class LogLoginController
 * @package app\http\admin\controller\sys
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