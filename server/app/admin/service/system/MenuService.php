<?php

namespace app\admin\service\system;

use app\common\enum\RedisKeyEnum;
use app\common\model\system\AdminRoleModel;
use app\common\model\system\MenuModel;
use app\common\model\system\RoleMenuModel;
use support\Cache;
use support\exception\RespBusinessException;

class MenuService
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
            $roleIds = AdminRoleModel::query()->where('admin_id', AdminService::getCurrentLoginId())->pluck('role_id');
            if (empty($roleIds)) {
                throw new RespBusinessException('用户未分配角色');
            }
            $menuIds = self::getRoleMenuIdsCache($roleIds);
            if (empty($menuIds)) {
                throw new RespBusinessException('用户角色未分配菜单');
            }
            $menus = self::getMenuIdsCache($menuIds);
            return self::filterAsyncRoutes($menus);
        } catch (\Exception $e) {
            exceptionLog($e);
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
            'isExt' => $menu['is_ext'],
            'extOpenMode' => $menu['ext_open_mode'],
            'type' => $menu['type'],
            'orderNo' => $menu['order_no'],
            'show' => $menu['show'],
            'activeMenu' => $menu['active_menu'],
            'status' => $menu['status'],
            'keepAlive' => $menu['keep_alive'],
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


    /**
     * 获取角色菜单id缓存
     * @param object $roleIds
     * @return array
     */
    public static function getRoleMenuIdsCache(object $roleIds): array
    {
        $menuIds = [];
        $roleMenuList = Cache::get(RedisKeyEnum::ROLE_MENU_IDS->value);
        // 如果缓存中没有数据，从数据库中查询并更新缓存
        if (empty($roleMenuList)) {
            $roleMenuList = RoleMenuModel::query()->select('menu_id', 'role_id')->get();
            // 转换为以 role_id 为键，menu_id 数组为值的数组
            $roleMenuMap = [];
            foreach ($roleMenuList as $item) {
                $roleMenuMap[$item['role_id']][] = $item['menu_id'];
            }
            Cache::set(RedisKeyEnum::ROLE_MENU_IDS->value, $roleMenuMap);
        } else {
            // 如果缓存存在，直接使用缓存中的数据
            $roleMenuMap = $roleMenuList;
        }
        // 遍历角色ID数组，收集对应的菜单ID
        foreach ($roleIds as $role) {
            if (isset($roleMenuMap[$role])) {
                $menuIds = array_merge($menuIds, $roleMenuMap[$role]);
            }
        }
        // 去除重复的menu_id并返回结果
        return array_unique($menuIds);
    }

    /**
     * 获取菜单缓存
     * @param array $menuIds
     * @return array
     */
    private static function getMenuIdsCache(array $menuIds): array
    {
        $allMenus = Cache::get(RedisKeyEnum::ALL_MENU_IDS->value);
        if (empty($allMenus)) {
            $allMenus = MenuModel::query()
                ->orderBy('order_no', 'ASC')
                ->select(['parent_id', 'type', 'id', 'active_menu', 'ext_open_mode', 'icon', 'is_ext', 'keep_alive', 'order_no', 'show', 'status', 'type', 'component', 'name', 'path'])
                ->get()
                ->keyBy('id')
                ->toArray();
            Cache::set(RedisKeyEnum::ALL_MENU_IDS->value, $allMenus);
        }
        // 过滤出指定 ID 的菜单信息
        $data = [];
        foreach ($menuIds as $menuId) {
            // array_key_exists 比 isset 稍微快一点，因为它不需要传第二个参数
            if (array_key_exists($menuId, $allMenus)) {
                $data[$menuId] = $allMenus[$menuId];
            }
        }
        return $data;
    }
}