<?php

namespace app\http\generate\controller;

use app\http\generate\service\GenerateService;
use app\http\generate\service\TableService;
use support\Request;
use support\Response;

class GenerateController
{
    /**
     * 测试
     * @return Response
     */
    public function test(): Response
    {
        $data = [
            'module_name' => 'admin',
            'class_dir' => '',
            'table_name' => 'user',
            'class_comment' => '用户管理',
            'lists' => 1,
            'create' => 0,
            'update' => 1,
            'delete' => 1,
            'detail' => 0
        ];
        GenerateService::generate($data);
        return renderSuccess('生成成功');
    }

    /**
     * 获取所有数据表
     * @param Request $request
     * @return Response
     */
    public function dataSheet(Request $request): Response
    {
        $list = TableService::tableSheet($request->all());
        return renderSuccess(compact('list'));
    }
}