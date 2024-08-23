<?php

namespace app\http\admin\service\system;

use app\common\model\sys\SysAdminRoleModel;
use app\common\model\sys\SysMenuModel;
use app\common\model\sys\SysRoleMenuModel;
use support\exception\RespBusinessException;

class SysPermissionsService
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
            $roleIds = SysAdminRoleModel::query()->where('admin_id', SysAdminService::getCurrentLoginId())->pluck('role_id');
            if (empty($roleIds)) {
                throw new RespBusinessException('用户未分配角色');
            }
            $menuIds = SysRoleMenuModel::query()->whereIn('role_id', $roleIds)->pluck('menu_id');
            if (empty($menuIds)) {
                throw new RespBusinessException('用户角色未分配菜单');
            }
            return SysMenuModel::query()->whereIn('id', $menuIds)
                ->whereNotNull('permission')
                ->whereIn('type', [1, 2])
                ->pluck('permission')->toArray();
        } catch (\Exception $e) {
            throw new RespBusinessException($e->getMessage());
        }
    }
}