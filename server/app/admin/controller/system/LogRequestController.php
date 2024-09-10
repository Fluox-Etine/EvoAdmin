<?php
declare (strict_types=1);

namespace app\admin\controller\system;

use support\Request;
use support\Response;
use support\exception\RespBusinessException;
use app\admin\logic\system\LogRequestLogic as SystemLogRequestLogic;

/**
 * 请求日志控制器类
 * Class LogRequestController
 * @package app\admin\controller\system
 * @date 2024/09/10 20:21
 */
class LogRequestController
{


    /**
     * 获取请求日志列表
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     * @date 2024/09/10 20:21
     */
    public function list(Request $request): Response
    {
        $params = $request->post();
        $list = SystemLogRequestLogic::handleLists($params);
        return renderSuccess($list,'列表获取成功');
    }

    /**
     * 获取请求日志详情
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     * @date 2024/09/10 20:21
     */
    public function detail(Request $request): Response
    {
        $params = $request->post();
        $result = SystemLogRequestLogic::handleDetail($params);
        return renderSuccess($result);
    }

}