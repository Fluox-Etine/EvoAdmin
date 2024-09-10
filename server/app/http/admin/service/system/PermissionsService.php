<?php

namespace app\http\admin\service\system;

use app\common\model\system\AdminRoleModel;
use app\common\model\system\MenuModel;
use app\common\model\system\RoleMenuModel;
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
            $menuIds = RoleMenuModel::query()->whereIn('role_id', $roleIds)->pluck('menu_id');
            if (empty($menuIds)) {
                throw new RespBusinessException('用户角色未分配菜单');
            }
            return MenuModel::query()->whereIn('id', $menuIds)
                ->whereNotNull('permission')
                ->whereIn('type', [1, 2])
                ->pluck('permission')->toArray();
        } catch (\Exception $e) {
            throw new RespBusinessException($e->getMessage());
        }
    }
}