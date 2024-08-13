<?php

namespace app\http\generate\controller;

use app\http\generate\service\GenerateService;

class GenerateController
{
    public function index()
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
}