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
     * @param string $fileName
     * @param int $uploadId // 上传者id
     * @param int $groupId // 文件分组id
     * @param int $channel // 上传渠道
     * @param int $fileType // 文件类型
     * @return array
     * @throws RespBusinessException
     */
    public static function handleUpload($file, string $fileName, int $uploadId, int $groupId = 0, int $channel = 0, int $fileType = 0): array
    {
        try {
            $fileInfo = UploadService::handleUploadService($file, $fileName);

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
            // 这里统一接收上一层的错误信息。
            throw new RespBusinessException($e->getMessage());
        }
    }


    /**
     * 合并切片文件
     * @param string $hash
     * @param string $fileName
     * @param int $uploadId
     * @param int $groupId
     * @param int $channel
     * @param int $fileType
     * @return bool
     * @throws RespBusinessException
     */
    public static function handleMerge(string $hash, string $fileName, int $uploadId, int $groupId = 0, int $channel = 0, int $fileType = 0): bool
    {
        try {
            $fileInfo = UploadService::handleChunkMergeServer($hash, $fileName);
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
            return true;
        } catch (\Exception $e) {
            throw new RespBusinessException($e->getMessage());
        }
    }
}