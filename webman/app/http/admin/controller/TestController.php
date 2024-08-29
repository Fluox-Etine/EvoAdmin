<?php

namespace app\http\admin\controller;

use app\common\service\UploadService;
use support\Request;

class TestController
{

    public function test(Request $request)
    {
        try {
            $file = $request->file('iFile');
            $result = UploadService::handleUpload($file);
            return renderSuccess($result);
        } catch (\Exception $e) {
            return renderError($e->getMessage());
        }
    }
}