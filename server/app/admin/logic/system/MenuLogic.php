<?php

namespace app\admin\logic\system;

use app\common\model\system\MenuModel;
use app\common\model\system\RoleMenuModel;
use support\exception\RespBusinessException;

class MenuLogic
{
    /**
     * 菜单列表
     * @param array $params
     * @return array
     */
    public static function list(array $params): array
    {
        $param = setQueryDefaultValue($params, [
            'name' => null,
            'path' => null,
            'component' => null
        ]);
        $filter = [];
        !is_null($param['name']) && $filter[] = ['name', 'like', '%' . $param['name'] . '%'];
        !is_null($param['path']) && $filter[] = ['path', 'like', '%' . $param['path'] . '%'];
        !is_null($param['component']) && $filter[] = ['component', 'like', '%' . $param['component'] . '%'];

        $list = MenuModel::query()->where($filter)->orderBy('order_no')->get();
        if (empty($list)) {
            return [];
        }
        $list = self::filterMenuToTable($list->toArray());
        if (!empty($list)) {
            return self::deleteEmptyChildren($list);
        }
        return $list;
    }


    /**
     * 获取所有菜单以及权限
     * @param array $menus
     * @param array $parentMenu
     * @return array
     */
    protected static function filterMenuToTable(array $menus, array $parentMenu = []): array
    {
        $res = [];
        foreach ($menus as $menu) {
            // 根级别菜单渲染
            $realMenu = null;
            if (!$parentMenu && !$menu['parent_id'] && $menu['type'] == 1) {
                // 根菜单，查找该跟菜单下子菜单，因为可能会包含权限
                $childMenu = self::filterMenuToTable($menus, $menu);
                $realMenu = $menu;
                $realMenu['children'] = $childMenu;
            } elseif (!$parentMenu && !$menu['parent_id'] && $menu['type'] == 0) {
                // 根目录
                $childMenu = self::filterMenuToTable($menus, $menu);
                $realMenu = $menu;
                $realMenu['children'] = $childMenu;
            } elseif ($parentMenu && $parentMenu['id'] == $menu['parent_id'] && $menu['type'] == 1) {
                // 子菜单下继续找是否有子菜单
                $childMenu = self::filterMenuToTable($menus, $menu);
                $realMenu = $menu;
                $realMenu['children'] = $childMenu;
            } elseif ($parentMenu && $parentMenu['id'] == $menu['parent_id'] && $menu['type'] == 0) {
                // 如果还是目录，继续递归
                $childMenu = self::filterMenuToTable($menus, $menu);
                $realMenu = $menu;
                $realMenu['children'] = $childMenu;
            } elseif ($parentMenu && $parentMenu['id'] == $menu['parent_id'] && $menu['type'] == 2) {
                $realMenu = $menu;
            }
            if ($realMenu) {
                $realMenu['pid'] = $menu['id'];
                $res[] = $realMenu;
            }
        }
        return $res;
    }


    /**
     * 删除空children
     * @param array $arr
     * @return array
     */
    protected static function deleteEmptyChildren(array $arr): array
    {
        foreach ($arr as &$node) {
            if (isset($node['children']) && count($node['children']) === 0) {
                unset($node['children']);
            } elseif (isset($node['children'])) {
                self::deleteEmptyChildren($node['children']);
            }
        }
        return $arr;
    }


    /**
     * 创建菜单
     * @param array $params
     * @return bool
     * @throws RespBusinessException
     */
    public static function create(array $params): bool
    {
        try {
            self::checkMenuParams($params);

            MenuModel::insert([
                'name' => $params['name'],
                'parent_id' => $params['parent_id'],
                'path' => $params['path'],
                'icon' => $params['icon'],
                'is_ext' => $params['is_ext'],
                'order_no' => $params['order_no'],
                'show' => $params['show'],
                'status' => $params['status'],
                'type' => $params['type'],
                'permission' => $params['permission'],
                'created_at' => time(),
                'updated_at' => time(),
                'component' => $params['component'],
                'keep_alive' => $params['keep_alive'] ?? 1
            ]);
            return true;
        } catch (\Exception $e) {
            throw new RespBusinessException($e->getMessage());
        }
    }


    /**
     * 更新菜单
     * @param array $params
     * @return bool
     * @throws RespBusinessException
     */
    public static function update(array $params): bool
    {
        try {
            self::checkMenuParams($params);
            $params['updated_at'] = time();
            return MenuModel::query()->where('id', $params['id'])->update($params) != false;
        } catch (\Exception $e) {
            throw new RespBusinessException($e->getMessage());
        }
    }

    /**
     * 菜单参数校验
     * @param array $params
     * @return void
     * @throws \Exception
     */
    protected static function checkMenuParams(array $params): void
    {
        // 无法直接创建权限，必须有parent
        if ($params['type'] == 2 && !$params['parent_id']) {
            throw new \Exception('权限必须包含父节点');
        }
        if ($params['type'] == 1 && $params['parent_id']) {
            $parent = MenuModel::find($params['parent_id'])->toArray();
            if (empty($parent)) {
                throw new \Exception('父级菜单不存在');
            }
            if ($parent['type'] == 1) {
                throw new \Exception('非法操作：该节点仅支持目录类型父节点');
            }
        }
    }


    /**
     * 删除菜单
     * @param int $id
     * @return bool
     * @throws RespBusinessException
     */
    public static function delete(int $id): bool
    {
        try {
            // 检查是否有角色关联
            $role = RoleMenuModel::query()->where('menu_id', $id)->first();
            if ($role) {
                throw new \Exception('该菜单已关联角色，请先解除关联关系');
            }
            // 如果有子菜单，则删除子菜单，一起删除子菜单
            $childMenus = MenuModel::query()->where('parent_id', $id)->pluck('id')->toArray();
            $ids = array_merge([$id], $childMenus);
            return MenuModel::destroy($ids) != false;
        } catch (\Exception $e) {
            throw new RespBusinessException($e->getMessage());
        }
    }

    /**
     * 获取后端定义的所有权限集
     * @return array
     * @throws RespBusinessException
     */
    public static function permissions(): array
    {
        try {
            $permissions = MenuModel::pluck('permission')->toArray();
            // 过滤掉空值
            $filteredPermissions = array_filter($permissions, function ($item) {
                return $item;
            });
            // 去除重复值
            $uniquePermissions = array_unique($filteredPermissions);
            // 重新索引数组
            return array_values($uniquePermissions);
        } catch (\Exception $e) {
            throw new RespBusinessException($e->getMessage());
        }
    }

}