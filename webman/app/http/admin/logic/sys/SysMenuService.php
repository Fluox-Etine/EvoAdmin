<?php

namespace app\http\admin\logic\sys;

use app\common\model\sys\SysAdminRoleModel;
use app\common\model\sys\SysMenuModel;
use app\http\admin\service\system\SysAdminService;
use support\exception\RespBusinessException;

class SysMenuService
{

    /**
     * 获取菜单列表
     * @return array
     * @throws RespBusinessException
     */
    public static function handleLoginMenuList(): array
    {
        try {
            // 查询对应的roleIds
            $roleIds = SysAdminRoleModel::query()->where('admin_id', SysAdminService::getCurrentLoginId())->pluck('role_id');
            if (empty($roleIds)) {
                throw new RespBusinessException('用户未分配角色');
            }
            $menuIds = SysAdminRoleModel::query()->whereIn('role_id', $roleIds->toArray())->pluck('menu_id');
            if (empty($menuIds)) {
                throw new RespBusinessException('用户角色未分配菜单');
            }
            $menus = SysMenuModel::query()->whereIn('id', $menuIds->toArray())
                ->orderBy('order_no', 'ASC')
                ->select(['parent_id', 'type', 'id', 'active_menu', 'ext_open_mode', 'icon', 'is_ext', 'keep_alive', 'order_no', 'show', 'status', 'type', 'component', 'name', 'path'])
                ->get()
                ->toArray();
            return self::filterAsyncRoutes($menus);
        } catch (\Exception $e) {
            throw new RespBusinessException($e->getMessage());
        }
    }



    /**
     * 递归过滤异步路由
     * @param array $menus
     * @param $parentRoute
     * @return array
     */
    protected static function filterAsyncRoutes(array $menus, $parentRoute = null): array
    {
        $res = [];
        foreach ($menus as $menu) {
            //// 如果是权限或禁用直接跳过
            if ($menu['type'] == 2 || !$menu['status']) {
                continue;
            }
            // 根级别菜单渲染
            $realRoute = null;

            $genFullPath = function (string $path, string $parentPath) {
                return self::uniqueSlash(str_starts_with($path, '/') ? $path : "/$parentPath/$path");
            };
            if (!$parentRoute && !$menu['parent_id'] && $menu['type'] == 1) {
                // 根菜单
                $realRoute = self::createRoute($menu);
            } elseif (!$parentRoute && !$menu['parent_id'] && $menu['type'] == 0) {
                $childRoutes = self::filterAsyncRoutes($menus, $menu);
                $realRoute = self::createRoute($menu);
                if ($childRoutes && count($childRoutes) > 0) {
                    $realRoute['redirect'] = $genFullPath($childRoutes[0]['path'], $realRoute['path']);
                    $realRoute['children'] = $childRoutes;
                }
            } elseif ($parentRoute && $parentRoute['id'] == $menu['parent_id'] && $menu['type'] === 1) {
                // 子菜单
                $realRoute = self::createRoute($menu);
            } elseif ($parentRoute && $parentRoute['id'] == $menu['parent_id'] && $menu['type'] === 0) {
                // 如果还是目录，继续递归
                $childRoutes = self::filterAsyncRoutes($menus, $menu);
                $realRoute = self::createRoute($menu);
                if ($childRoutes && count($childRoutes) > 0) {
                    $realRoute['redirect'] = $genFullPath($childRoutes[0]['path'], $realRoute['path']);
                    $realRoute['children'] = $childRoutes;
                }
            }
            // add current route
            if ($realRoute) {
                $res[] = $realRoute;
            }
        }

        return $res;
    }



    /**
     * 创建路由
     * @param array $menu
     * @return array
     */
    protected static function createRoute(array $menu): array
    {
        $commonMeta = [
            'title' => $menu['name'],
            'icon' => $menu['icon'],
            'is_ext' => $menu['is_ext'],
            'ext_open_mode' => $menu['ext_open_mode'],
            'type' => $menu['type'],
            'order_no' => $menu['order_no'],
            'show' => $menu['show'],
            'active_menu' => $menu['active_menu'],
            'status' => $menu['status'],
            'keep_alive' => $menu['keep_alive'],
        ];

        // 目录
        if ($menu['type'] === 0) {
            return [
                'id' => $menu['id'],
                'path' => $menu['path'],
                'component' => $menu['component'],
                'name' => $menu['name'],
                'meta' => $commonMeta,
            ];
        }
        if (self::isExternal($menu['path'])) {
            return [
                'id' => $menu['id'],
                'path' => $menu['path'],
                'component' => 'IFrame', // 根据需要取消注释
                'name' => $menu['name'],
                'meta' => $commonMeta,
            ];
        }
        return [
            'id' => $menu['id'],
            'path' => $menu['path'],
            'name' => $menu['name'],
            'component' => $menu['component'],
            'meta' => $commonMeta,
        ];
    }


    /**
     * 去除重复的斜杠
     * @param string $path
     * @return array|string|string[]|null
     */
    protected static function uniqueSlash(string $path): array|string|null
    {
        return preg_replace('/(https?:\/\/)|(\/)+/', '$1$2', $path);
    }

    /**
     * 是否为外部链接
     * @param string $path
     * @return bool
     */
    protected static function isExternal(string $path): bool
    {
        // 检查字符串是否以 http 或 https 开头
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return true;
        } else {
            return false;
        }
    }
}