<?php

namespace app\common\service;

class UploadService
{
    /**
     * 上传文件
     * @param $file
     * @return array
     * @throws \Exception
     */
    public static function handleUploadService($file): array
    {
        $typeCode = self::getFileCode($file->getRealPath());
        $allowExt = config('env.upload.allow_ext');
        // 验证格式类型
        if (!isset($allowExt[$typeCode])) {
            throw new \Exception('文件类型不允许');
        }
        $fileExt = $allowExt[$typeCode];
        // 判断文件大小
        if ($file->getSize() > config('env.upload.max_size')) {
            throw new \Exception('文件大小超出限制');
        }
        $fileSize = $file->getSize();
        $fileName = bin2hex(pack('Nn', time(), rand(1, 65535))) . '.' . $fileExt;
        if ($file->move(public_path() . config('env.upload.upload_dir') . $fileName)) {
            return [
                'file_name' => $fileName,
                'file_path' => config('env.upload.upload_dir') . $fileName,
                'file_size' => $fileSize,
                'file_type' => $fileExt,
                'preview_url' => config('env.upload.domain') . config('env.upload.upload_dir') . $fileName,
            ];
        }
        throw new \Exception('文件上传失败');
    }


    /**
     * 获取文件头部
     * @param string $filePath
     * @return int
     */
    private static function getFileCode(string $filePath): int
    {
        // TODO 其他文件的code验证，百度不到，只能自己一个个实验，获取到了。
        $fp = fopen($filePath, 'rb');
        $bin = fread($fp, 2);
        fclose($fp);
        $strInfo = unpack("C2chars", $bin);
        return intval($strInfo['chars1'] . $strInfo['chars2']);
    }
}