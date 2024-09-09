<?php
declare (strict_types=1);

namespace app\http\admin\logic\upload;

use app\common\model\system\AdminModel;
use app\common\model\upload\FileModel as UploadFileModel;
use app\common\model\upload\GroupModel as UploadGroupModel;
use support\exception\RespBusinessException;

/**
 * 文件资源逻辑类
 * Class FileLogic
 * @package app\http\admin\logic\upload
 * @date 2024/09/07 17:26
 */
class FileLogic
{


    /**
     * 获取文件资源列表
     * @param array $params
     * @return array
     * @throws RespBusinessException
     * @date 2024/09/07 17:26
     */
    public static function handleLists(array $params): array
    {
        try {
            $filter = [];
            $param = setQueryDefaultValue($params, [
                'channel' => null,
                'file_ext' => null,
                'file_name' => null,
                'file_path' => null,
                'file_type' => null,
                'group_id' => null
            ]);

            !empty($param['channel']) && $filter[] = ['channel', '=', $param['channel']];
            !empty($param['file_ext']) && $filter[] = ['file_ext', 'like', '%' . $param['file_ext'] . '%'];
            !empty($param['file_name']) && $filter[] = ['file_name', 'like', '%' . $param['file_name'] . '%'];
            !empty($param['file_path']) && $filter[] = ['file_path', 'like', '%' . $param['file_path'] . '%'];
            !empty($param['file_type']) && $filter[] = ['file_type', '=', $param['file_type']];
            !empty($param['group_id']) && $filter[] = ['group_id', '=', $param['group_id']];

            $list = UploadFileModel::query()->where($filter)->orderByDesc('id')->select('channel', 'created_at', 'file_name', 'file_ext', 'file_path', 'file_size', 'file_type', 'group_id', 'id', 'uploader_id')->paginate($params["pageSize"] ?? 10);
            return formattedPaginate($list);
        } catch (\Exception $e) {
            throw new RespBusinessException('查询数据异常');
        }
    }

    /**
     * 文件资源详情
     * @param array $params
     * @return array
     * @throws RespBusinessException
     * @date 2024/09/07 17:26
     */
    public static function handleDetail(array $params): array
    {
        try {
            $detail = UploadFileModel::query()->where('id', $params['id'])->select('*')->first();
            // 判断文件是用户端还是管理端
            if ($detail->channel == 10) {
                $detail->uploader_name = AdminModel::query()->where('id', $detail->uploader_id)->value('username') ?? '未知管理员用户';
            } elseif ($detail->channel == 20) {
                $detail->uploader_name = '前端用户未知';
            } else {
                $detail->uploader_name = '非法上传渠道';
            }
            if ($detail->group_id > 0) {
                $detail->group_name = UploadGroupModel::query()->where('id', $detail->group_id)->value('name') ?? '未知分组';
            } else {
                $detail->group_name = '全部分组';
            }
            if (is_null($detail)) {
                // TODO 抛出异常（直接返回，不经过异常）
                throw new \Exception('数据不存在');
            }
            return $detail->toArray();
        } catch (\Exception $e) {
            exceptionLog($e);
            throw new RespBusinessException('查询数据详情异常');
        }
    }

    /**
     * 删除文件资源
     * @param array $params
     * @return bool
     * @throws RespBusinessException
     * @date 2024/09/07 17:26
     */
    public static function handleDelete(array $params): bool
    {
        try {
            if (is_array($params['id'])) {
                // 批量删除
                $filePathList = UploadFileModel::query()->whereIn('id', $params['id'])->pluck('file_path');
                foreach ($filePathList as $filePath) {
                    deleteFile($filePath);
                }
                return UploadFileModel::query()->whereIn('id', $params['id'])->delete() != false;
            }
            // 查询文件路径地址
            $filePath = UploadFileModel::query()->where('id', $params['id'])->value('file_path');
            // 删除文件
            deleteFile($filePath);
            // 删除数据
            return UploadFileModel::query()->where('id', $params['id'])->delete() != false;
        } catch (\Exception $e) {
            exceptionLog($e);
            throw new RespBusinessException('删除数据异常');
        }
    }
}