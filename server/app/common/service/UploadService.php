<?php

namespace app\common\service;

class UploadService
{
    /**
     * 上传文件
     * @param $file
     * @param string $fileName
     * @return array
     * @throws \Exception
     */
    public static function handleUploadService($file, string $fileName): array
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
        $name = bin2hex(pack('Nn', time(), rand(1, 65535))) . '.' . $fileExt;
        if (empty($fileName)) {
            $fileName = $name;
        }
        check_dir(config('env.upload.upload_dir'));
        if ($file->move(public_path() . config('env.upload.upload_dir') . $name)) {
            return [
                'file_name' => $fileName,
                'file_path' => config('env.upload.upload_dir') . $name,
                'file_size' => $fileSize,
                'file_type' => $fileExt,
                'preview_url' => config('env.upload.domain') . config('env.upload.upload_dir') . $name,
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


    /**
     * 处理分片上传
     * @param $chunk
     * @param string $hash
     * @param int $index
     * @param string $fileName
     * @return bool
     */
    public static function handleChunkServer($chunk, string $hash, int $index, string $fileName): bool
    {
        $path = public_path() . config('env.upload.chunk_dir') . $hash . '/' . md5($fileName) . '/chunk_' . $index;
        return $chunk->move($path) != false;
    }

    /**
     * 合并分片文件
     * @param string $hash
     * @param string $fileName
     * @return array
     * @throws \Exception
     */
    public static function handleChunkMergeServer(string $hash, string $fileName): array
    {
        $chunksDir = public_path() . config('env.upload.chunk_dir') . $hash . '/' . md5($fileName);
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileName = bin2hex(pack('Nn', time(), rand(1, 65535))) . '.' . $ext;
        $filePath = public_path() . config('env.upload.upload_dir') . $fileName;
        // 创建上传目录
        check_dir(config('env.upload.upload_dir'));
        // 创建文件
        $fileHandle = fopen($filePath, 'w');

        // 扫描切片目录，获取切片列表
        $chunkList = scandir($chunksDir);
        if (empty($chunkList)) {
            throw new \Exception('切片文件不存在');
        }
        // 按照切片编号排序
        sort($chunkList, SORT_NUMERIC);
        // 逐个读取切片文件，写入新文件
        foreach ($chunkList as $chunk) {
            if ($chunk !== '.' && $chunk !== '..' && $chunk !== '.DS_Store') {
                fwrite($fileHandle, file_get_contents("$chunksDir/$chunk"));
            }
        }
        fclose($fileHandle);
        // 删除切片文件
        if (is_dir($chunksDir)) {
            $files = glob($chunksDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($chunksDir);
        }
        // 判断文件是否存在
        var_dump($filePath);
        var_dump(file_exists($filePath));
        if (file_exists($filePath)) {
            return [
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => filesize($filePath),
                'file_type' => $ext,
                'preview_url' => config('env.upload.domain') . $filePath,
            ];
        }
        throw new \Exception('文件合并失败');
    }
}