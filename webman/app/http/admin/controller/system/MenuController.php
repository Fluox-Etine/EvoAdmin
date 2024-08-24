<?php

namespace app\http\admin\controller\system;

use app\http\admin\logic\system\SysMenuLogic;
use support\exception\RespBusinessException;
use support\Request;
use support\Response;

class MenuController
{
    /**
     * 菜单列表
     * @return Response
     */
    public function list(): Response
    {
        $fields = ['name', 'path', 'component'];
        $params = formattedRequest($fields);
        $list = SysMenuLogic::list($params);
        var_dump($list);
        return renderSuccess($list);
    }

    /**
     * 创建菜单
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     */
    public function create(Request $request): Response
    {
        // TODO 后期增加数据校验功能
        $data = $this->processRequestData($request);
        if ((new SysMenuLogic())->create($data)) {
            return renderSuccess("创建菜单成功");
        }
        return renderError("创建菜单失败");
    }


    /**
     * 更新菜单
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     */
    public function update(Request $request): Response
    {
        if ((new SysMenuLogic())->update($request->post())) {
            return renderSuccess("更新菜单成功");
        }
        return renderError("更新菜单失败");
    }

    /**
     * 删除菜单
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     */
    public function delete(Request $request): Response
    {
        if (SysMenuLogic::delete($request->post('id'))) {
            return renderSuccess("删除菜单成功");
        }
        return renderError("删除菜单失败");
    }

    /**
     * 获取后端定义的所有权限集
     * @return Response
     * @throws RespBusinessException
     */
    public function permissions(): Response
    {
        $data = SysMenuLogic::permissions();
        return renderSuccess($data);
    }

    /**
     * 处理请求数据
     * @param $request
     * @return array
     */
    private function processRequestData($request): array
    {
        // 从请求中获取参数值，如果参数不存在则设置为默认值
        $parent_id = $request->post('parent_id', null);
        $path = $request->post('path', null);
        $permission = $request->post('permission', null);
        $icon = $request->post('icon', '');
        $component = $request->post('component', null);
        $keep_alive = $request->post('keep_alive', 1);
        $show = $request->post('show', 1);
        // 将参数合并到一个新数组中
        return array_merge($request->post(), [
            'parent_id' => $parent_id,
            'path' => $path,
            'permission' => $permission,
            'icon' => $icon,
            'component' => $component,
            'keep_alive' => $keep_alive,
            'show' => $show,
        ]);
    }
}