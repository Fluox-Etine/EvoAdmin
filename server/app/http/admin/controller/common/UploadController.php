<?php

namespace app\http\admin\controller\common;

use app\common\service\UploadService;
use support\Request;
use support\Response;

class UploadController
{
    /**
     * 文件上传
     * @param Request $request
     * @return Response
     */
    public function upload(Request $request): Response
    {
        try {
            $file = $request->file('file');
            $result = UploadService::handleUpload($file);
            return renderSuccess($result);
        } catch (\Exception $e) {
            return renderError($e->getMessage());
        }
    }
}