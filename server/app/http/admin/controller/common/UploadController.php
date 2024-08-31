<?php

namespace app\http\admin\controller\common;

use app\common\logic\UploadLogic;
use app\common\service\UploadService;
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
            $request->post('fileName'),
            SysAdminService::getCurrentLoginId(),
            $request->post('group', 0),
            10,
            $request->post('type', 10)
        );
        return renderSuccess($result);
    }


    /**
     * 文件切片上传
     * @param Request $request
     * @return Response
     */
    public function chunk(Request $request): Response
    {
        $file = $request->file('chunk');
        $hash = $request->post('hash');
        $index = (int)$request->post('index');
        $fileName = $request->post('fileName');
        if (empty($file) || empty($hash) || $index < 0 || empty($fileName)) {
            return renderError('切片参数错误');
        }
        try {
            $result = UploadService::handleChunkServer($file, $hash, $index, $fileName);
            return $result ? renderSuccess() : renderError();
        } catch (\Exception $e) {
            return renderError('第' . $index . '片上传失败');
        }
    }

    /**
     * 合并切片文件
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     */
    public function merge(Request $request): Response
    {

        $result = UploadLogic::handleMerge(
            $request->post('hash'),
            $request->post('fileName'),
            SysAdminService::getCurrentLoginId(),
            $request->post('group', 0),
            10,
            $request->post('type', 10)
        );
        return $result ? renderSuccess() : renderError();
    }
}