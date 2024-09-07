<?php
declare (strict_types=1);

namespace app\http\admin\logic\upload;

use app\common\model\upload\FileModel as UploadFileModel;
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
            $param = setQueryDefaultValue($params,[
                'channel' => null,
                'file_ext' => null,
                'file_name' => null,
                'file_path' => null,
                'file_type' => null,
                'group_id' => null
            ]);

            !empty($param['channel']) && $filter[] = ['channel','=', $param['channel']];
            !empty($param['file_ext']) && $filter[] = ['file_ext','like','%'. $param['file_ext']. '%'];
            !empty($param['file_name']) && $filter[] = ['file_name','like','%'. $param['file_name']. '%'];
            !empty($param['file_path']) && $filter[] = ['file_path','like','%'. $param['file_path']. '%'];
            !empty($param['file_type']) && $filter[] = ['file_type','=', $param['file_type']];
            !empty($param['group_id']) && $filter[] = ['group_id','=', $param['group_id']];

            $list = UploadFileModel::query()->where($filter)->select('channel','created_at','file_name','file_path','file_size','file_type','group_id','id','uploader_id')->paginate($params["pageSize"] ?? 10);
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
            if(is_null($detail)){
                // TODO 抛出异常（直接返回，不经过异常）
                throw new \Exception('数据不存在');
            }
            return $detail->toArray();
        } catch (\Exception $e) {
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
            return UploadFileModel::query()->where('id', $params['id'])->delete() != false;
        } catch (\Exception $e) {
            throw new RespBusinessException('删除数据异常');
        }
    }
}