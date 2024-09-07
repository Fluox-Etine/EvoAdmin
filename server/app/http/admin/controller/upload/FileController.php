<?php
declare (strict_types=1);

namespace app\http\admin\controller\upload;

use support\Request;
use support\Response;
use support\exception\RespBusinessException;
use app\http\admin\logic\upload\FileLogic as UploadFileLogic;

/**
 * 文件资源控制器类
 * Class FileController
 * @package app\http\admin\controller\upload
 * @date 2024/09/07 17:26
 */
class FileController
{


    /**
     * 获取文件资源列表
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     * @date 2024/09/07 17:26
     */
    public function list(Request $request): Response
    {
        $params = $request->post();
        $list = UploadFileLogic::handleLists($params);
        return renderSuccess($list,'列表获取成功');
    }

    /**
     * 删除文件资源
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     * @date 2024/09/07 17:26
     */
    public function deleted(Request $request): Response
    {
        $params = $request->post();
        $result = UploadFileLogic::handleDelete($params);
        return $result ? renderSuccess('删除成功') : renderError('删除失败');
    }

    /**
     * 获取文件资源详情
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     * @date 2024/09/07 17:26
     */
    public function detail(Request $request): Response
    {
        $params = $request->post();
        $result = UploadFileLogic::handleDetail($params);
        return renderSuccess($result);
    }

}