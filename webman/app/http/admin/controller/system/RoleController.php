<?php

namespace app\http\admin\controller\system;

use app\http\admin\logic\system\SysRoleLogic;
use support\exception\RespBusinessException;
use support\Request;
use support\Response;

class RoleController
{
    /**
     * 角色列表
     * @return Response
     */
    public function list(): Response
    {
        $fields = ['pageSize', 'name', 'value', 'status', 'remark'];
        $params = formattedRequest($fields);
        $list = SysRoleLogic::list($params);
        return renderSuccess($list);
    }

    /**
     * 创建角色
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     */
    public function create(Request $request): Response
    {
        // TODO 后期增加数据校验功能
        if (SysRoleLogic::create($request->post())) {
            return renderSuccess("创建角色成功");
        }
        return renderError("创建角色失败");
    }

    /**
     * 更新角色
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     */
    public function update(Request $request): Response
    {
        if (SysRoleLogic::update($request->post(), $request->get('id'))) {
            return renderSuccess("更新角色成功");
        }
        return renderError("更新角色失败");
    }

    /**
     * 删除角色
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     */
    public function delete(Request $request): Response
    {
        if (SysRoleLogic::delete($request->get())) {
            return renderSuccess("删除角色成功");
        }
        return renderError("删除角色失败");
    }

    /**
     * 角色详情
     * @param Request $request
     * @return Response
     */
    public function detail(Request $request): Response
    {
        $detail = SysRoleLogic::detail($request->get('id'));
        return renderSuccess($detail);
    }
}