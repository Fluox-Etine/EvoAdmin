<?php

namespace app\http\admin\controller\system;

use app\http\admin\logic\system\RoleLogic;
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
        $list = RoleLogic::list($params);
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
        if (RoleLogic::create($request->post())) {
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
        if (RoleLogic::update($request->post())) {
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
        if (RoleLogic::delete($request->get())) {
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
        $detail = RoleLogic::detail($request->get('id'));
        return renderSuccess($detail);
    }
}