<?php
declare (strict_types=1);

namespace app\admin\controller\upload;

use app\admin\logic\upload\GroupLogic as UploadGroupLogic;
use app\admin\validate\upload\GroupValidate as UploadGroupValidate;
use support\exception\RespBusinessException;
use support\Request;
use support\Response;

/**
 * 文件分组控制器类
 * Class GroupController
 * @package app\admin\controller\upload
 * @date 2024/08/29 11:29
 */
class GroupController
{


    /**
     * 获取文件分组列表
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     * @date 2024/08/29 11:29
     */
    public function list(Request $request): Response
    {
        $params = $request->post();
        $list = UploadGroupLogic::handleLists($params);
        return renderSuccess($list, '列表获取成功');
    }

    /**
     * 添加文件分组
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     * @date 2024/08/29 11:29
     */
    public function create(Request $request): Response
    {
        $params = $request->post();
        UploadGroupValidate::createValidate($params);
        $result = UploadGroupLogic::handleCreate($params);
        return $result ? renderSuccess('添加成功') : renderError('添加失败');
    }

    /**
     * 编辑文件分组
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     * @date 2024/08/29 11:29
     */
    public function update(Request $request): Response
    {
        $params = $request->post();
        UploadGroupValidate::updateValidate($params);
        $result = UploadGroupLogic::handleUpdate($params);
        return $result ? renderSuccess('修改成功') : renderError('修改失败');
    }

    /**
     * 删除文件分组
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     * @date 2024/08/29 11:29
     */
    public function delete(Request $request): Response
    {
        $params = $request->post();
        $result = UploadGroupLogic::handleDelete($params);
        return $result ? renderSuccess('删除成功') : renderError('删除失败');
    }


    /**
     * 获取文件分组下拉列表
     * @return Response
     */
    public function select(): Response
    {
        $list = UploadGroupLogic::handleSelect();
        return renderSuccess($list);
    }

}