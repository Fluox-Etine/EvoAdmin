<?php
declare (strict_types=1);

namespace app\admin\logic\upload;

use app\common\model\upload\FileModel as UploadFileModel;
use app\common\model\upload\GroupModel as UploadGroupModel;
use support\Db;
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

            $list = UploadGroupModel::query()->where($filter)->select('id', 'name', 'sort', 'created_at', 'updated_at')->get();
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
        Db::beginTransaction();
        try {
            // 把当前分组下资源全部转移到默认分组
            UploadFileModel::query()->where('group_id', $params['id'])->update(['group_id' => 0]);
            UploadGroupModel::query()->where('id', $params['id'])->delete();
            Db::commit();
            return true;
        } catch (\Throwable $e) {
            Db::rollBack();
            throw new RespBusinessException('删除数据异常');
        }
    }

    /**
     * 获取分组下拉列表
     * @return array
     */
    public static function handleSelect(): array
    {
        $list = UploadGroupModel::query()->select('id as value', 'name as label')->orderByDesc('sort')->get();
        if ($list->isEmpty()) {
            return [
                [
                    'value' => 0,
                    'label' => '全部分组'
                ]
            ];
        } else {
            // 把 默认分组放到最前面
            $list = $list->toArray();
            array_unshift($list, [
                'value' => 0,
                'label' => '全部分组'
            ]);
            return $list;
        }
    }
}