<?php
declare (strict_types=1);

namespace app\http\admin\controller\upload;

use support\Request;
use support\Response;
use support\exception\RespBusinessException;
use app\http\admin\logic\upload\FileLogic as UploadFileLogic;

/**
 * 文件控制器类
 * Class FileController
 * @package app\http\api\controller\upload
 * @date 2024/08/29 20:47
 */
class FileController
{


    /**
     * 获取文件列表
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     * @date 2024/08/29 20:47
     */
    public function list(Request $request): Response
    {
        $params = $request->post();
        $list = UploadFileLogic::handleLists($params);
        return renderSuccess($list,'列表获取成功');
    }

    /**
     * 删除文件
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     * @date 2024/08/29 20:47
     */
    public function delete(Request $request): Response
    {
        $params = $request->post();
        $result = UploadFileLogic::handleDelete($params);
        return $result ? renderSuccess('删除成功') : renderError('删除失败');
    }

}