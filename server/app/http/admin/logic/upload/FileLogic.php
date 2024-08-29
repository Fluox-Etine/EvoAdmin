<?php
declare (strict_types=1);

namespace app\http\admin\logic\upload;

use app\common\model\upload\FileModel as UploadFileModel;
use support\exception\RespBusinessException;

/**
 * 文件逻辑类
 * Class FileLogic
 * @package app\api\logic\upload
 * @date 2024/08/29 20:47
 */
class FileLogic
{

    /**
     * 获取文件列表
     * @param array $params
     * @return array
     * @throws RespBusinessException
     * @date 2024/08/29 20:47
     */
    public static function handleLists(array $params): array
    {
        try {
            $filter = [];
            $param = setQueryDefaultValue($params, [
                'group_id' => null,
                'channel' => 10,
                'file_type' => null,
                'file_name' => null
            ]);

            !empty($param['group_id']) && $filter[] = ['group_id', '=', $param['group_id']];
            !empty($param['channel']) && $filter[] = ['channel', '=', $param['channel']];
            !empty($param['file_type']) && $filter[] = ['file_type', '=', $param['file_type']];
            !empty($param['file_name']) && $filter[] = ['file_name', 'like', '%' . $param['file_name'] . '%'];

            $list = UploadFileModel::query()->where($filter)->select('file_name', 'file_path', 'file_size', 'file_ext', 'uploader_id', 'created_at', 'file_type', 'channel', 'id')->paginate($params["pageSize"] ?? 10);
            return formattedPaginate($list);
        } catch (\Exception $e) {
            throw new RespBusinessException('查询数据异常');
        }
    }

    /**
     * 删除文件
     * @param array $params
     * @return bool
     * @throws RespBusinessException
     * @date 2024/08/29 20:47
     */
    public static function handleDelete(array $params): bool
    {
        try {
            return UploadFileModel::query()->where('id', $params['id'])->delete() != false;
        } catch (\Exception $e) {
            throw new RespBusinessException('删除数据异常');
        }
    }
}