<?php

namespace app\http\generate\controller;

use app\http\generate\service\GenerateService;
use app\http\generate\service\TableService;
use support\exception\RespBusinessException;
use support\Request;
use support\Response;

class GenerateController
{
    /**
     * sql分析
     * @param Request $request
     * @return Response
     */
    public function tableSql(Request $request): Response
    {
        return renderSuccess([]);
    }

    /**
     * 获取所有数据表
     * @return Response
     */
    public function dataSheet(): Response
    {
        $list = TableService::tableSheet();
        return renderSuccess($list);
    }

    /**
     * 获取表详情
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     */
    public function dataSheetDetail(Request $request): Response
    {
        $tableName = $request->get('tableName');
        $data = TableService::tableSheetDetail($tableName);
        return renderSuccess($data);
    }


    /**
     * 生成代码
     * @param Request $request
     * @return Response
     * @throws RespBusinessException
     */
    public function generate(Request $request): Response
    {
        $data = $request->post();
        $result = GenerateService::generate($data);
        return renderSuccess($result);
    }
}