<?php

namespace app\http\admin\controller\common;

use app\common\logic\UploadLogic;
use app\http\admin\service\system\SysAdminService;
use support\exception\RespBusinessException;
use support\Request;
use support\Response;

class UploadController
{
    /**
     * 文件上传
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     */
    public function upload(Request $request): Response
    {

        $file = $request->file('file');
        $result = UploadLogic::handleUpload(
            $file,
            SysAdminService::getCurrentLoginId(),
            $request->post('group', 0),
            10, $request->post('type', 10)
        );
        return renderSuccess($result);

    }
}