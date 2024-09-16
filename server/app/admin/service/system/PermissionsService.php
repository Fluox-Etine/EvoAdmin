<?php

namespace app\admin\service\system;

use app\common\enum\RedisKeyEnum;
use app\common\model\system\AdminRoleModel;
use app\common\model\system\MenuModel;
use support\Cache;
use support\exception\RespBusinessException;

class PermissionsService
{

    /**
     * 获取权限列表
     * @return array
     * @throws RespBusinessException
     */
    public static function handleLoginPermissionsList(): array
    {
        try {
            // 查询对应的roleIds
            $roleIds = AdminRoleModel::query()->where('admin_id', AdminService::getCurrentLoginId())->pluck('role_id');
            if (empty($roleIds)) {
                throw new RespBusinessException('用户未分配角色');
            }
            $menuIds = MenuService::getRoleMenuIdsCache($roleIds);
            if (empty($menuIds)) {
                throw new RespBusinessException('用户角色未分配菜单');
            }
            return self::getMenuIdsPermissionCache($menuIds);
        } catch (\Exception $e) {
            throw new RespBusinessException($e->getMessage());
        }
    }


    /**
     * 获取菜单缓存
     * @param array $menuIds
     * @return array
     */
    private static function getMenuIdsPermissionCache(array $menuIds): array
    {
        $permission = Cache::get(RedisKeyEnum::MENU_IDS_PERMISSION->value);
        if (empty($permission)) {
            $permission = MenuModel::query()->whereNotNull('permission')
                ->whereIn('type', [1, 2])
                ->select('permission', 'id')
                ->get()
                ->keyBy('id')
                ->toArray();
            Cache::set(RedisKeyEnum::MENU_IDS_PERMISSION->value, $permission);
        }
        $data = [];
        foreach ($menuIds as $menuId) {
            if (array_key_exists($menuId, $permission)) {
                $data[] = $permission[$menuId]['permission'];
            }
        }
        return $data;
    }

}