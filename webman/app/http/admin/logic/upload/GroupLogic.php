<?php
declare (strict_types=1);

namespace app\http\admin\logic\upload;

use app\common\model\upload\GroupModel as UploadGroupModel;
use support\exception\RespBusinessException;

/**
 * 文件分组逻辑类
 * Class GroupLogic
 * @package app\admin\logic\upload
 * @date 2024/08/29 11:29
 */
class GroupLogic
{


    /**
     * 获取文件分组列表
     * @param array $params
     * @return array
     * @throws RespBusinessException
     * @date 2024/08/29 11:29
     */
    public static function handleLists(array $params): array
    {
        try {
            $filter = [];
            $param = setQueryDefaultValue($params, [
                'name' => null,
            ]);

            !empty($param['name']) && $filter[] = ['name', 'like', '%' . $param['name'] . '%'];

            $list = UploadGroupModel::query()->where($filter)->select('id', 'name', 'sort', 'created_at')->get();
            return $list->isEmpty() ? [] : $list->toArray();
        } catch (\Exception $e) {
            throw new RespBusinessException('查询数据异常');
        }
    }

    /**
     * 添加文件分组
     * @param array $params
     * @return bool
     * @throws RespBusinessException
     * @date 2024/08/29 11:29
     */
    public static function handleCreate(array $params): bool
    {
        try {
            return UploadGroupModel::insert([
                    'name' => $params['name'],
                    'sort' => $params['sort'],
                    'created_at' => time(),
                    'updated_at' => time()
                ]) != false;
        } catch (\Exception $e) {
            throw new RespBusinessException('创建数据异常');
        }
    }

    /**
     * 更新文件分组
     * @param array $params
     * @return bool
     * @throws RespBusinessException
     * @date 2024/08/29 11:29
     */
    public static function handleUpdate(array $params): bool
    {
        try {
            return UploadGroupModel::query()->where('id', $params['id'])->update([
                    'name' => $params['name'],
                    'sort' => $params['sort'],
                    'updated_at' => time()
                ]) != false;
        } catch (\Exception $e) {
            throw new RespBusinessException('编辑数据异常');
        }
    }

    /**
     * 删除文件分组
     * @param array $params
     * @return bool
     * @throws RespBusinessException
     * @date 2024/08/29 11:29
     */
    public static function handleDelete(array $params): bool
    {
        try {
            return UploadGroupModel::query()->where('id', $params['id'])->delete() != false;
        } catch (\Exception $e) {
            throw new RespBusinessException('删除数据异常');
        }
    }
}