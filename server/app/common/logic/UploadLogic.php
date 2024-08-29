<?php

namespace app\common\logic;

use app\common\service\UploadService;
use support\exception\RespBusinessException;
use app\common\model\upload\FileModel as UploadFileModel;

class UploadLogic
{

    /**
     * 上传文件
     * @param $file // 上传文件
     * @param int $uploadId // 上传者id
     * @param int $groupId // 文件分组id
     * @param int $channel // 上传渠道
     * @param int $fileType // 文件类型
     * @return array
     * @throws RespBusinessException
     */
    public static function handleUpload($file, int $uploadId, int $groupId = 0, int $channel = 0, int $fileType = 0): array
    {
        try {
            $fileInfo = UploadService::handleUpload($file);

            UploadFileModel::insert([
                'group_id' => $groupId,
                'channel' => $channel,
                'file_type' => $fileType,
                'file_name' => $fileInfo['file_name'],
                'file_path' => $fileInfo['file_path'],
                'file_size' => $fileInfo['file_size'],
                'file_ext' => $fileInfo['file_type'],
                'uploader_id' => $uploadId,
                'created_at' => time(),
                'updated_at' => time()
            ]);

            return $fileInfo;
        } catch (\Exception $e) {
            // 这里统一接收上一层的一样错误信息。
            throw new RespBusinessException($e->getMessage());
        }
    }
}