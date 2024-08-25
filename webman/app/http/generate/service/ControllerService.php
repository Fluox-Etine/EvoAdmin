<?php

namespace app\http\generate\service;

class ControllerService
{

    /**
     * 生成控制器
     * @param array $params
     * @return string
     */
    public static function handleController(array $params): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NAMESPACE}',
            '{USE}',
            '{CLASS_COMMENT}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}',
            '{MODULE_NAME}',
            '{PACKAGE_NAME}',
            '{FUNCTIONS}'
        ];

        // 等待替换的内容
        $waitReplace = [
            GenerateService::getNameSpaceContent($params['moduleName'], $params['classDir'], $params['upperCameName'], 'controller'),
            self::getUseContent($params['moduleName'], $params['classDir'], $params['upperCameName']),
            $params['classComment'],
            $params['date'],
            $params['upperCameName'],
            $params['moduleName'],
            $params['packageName'],
            self::handleFunctions($params['methods'], $params['classComment'], $params['date'], $params['upperCameName'])
        ];

        $templatePath = GenerateService::getTemplatePath('php/controller');
        // 替换内容
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }


    /**
     * 获取use内容
     * @param string $moduleName
     * @param string $classDir
     * @param string $upperCameName
     * @return string
     */
    private static function getUseContent(string $moduleName, string $classDir, string $upperCameName): string
    {
        if (empty($classDir)) {
            $tpl = "use app\\http\\$moduleName\\logic\\" . $upperCameName . "Logic;";
        } else {
            $tpl = "use app\\http\\$moduleName\\logic\\" . $classDir . "\\" . $upperCameName . "Logic;";
        }
        return $tpl;
    }

    /**
     * 处理方法
     * @param array $method
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @return string
     */
    private static function handleFunctions(array $method, string $notes, string $date, string $upperCameName): string
    {
        $content = '';
        $methods = [
            'lists' => 'handleLists',
            'create' => 'handleCreate',
            'update' => 'handleUpdate',
            'delete' => 'handleDelete',
            'detail' => 'handleDetail', // 假设存在 handleDetail 方法
        ];
        foreach ($methods as $action => $methodName) {
            if (isset($method[$action]) && $method[$action]) {
                $content .= self::$methodName($notes, $date, $upperCameName) . PHP_EOL;
            }
        }
        return $content;
    }

    /**
     * 处理列表方法
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @return string
     */
    private static function handleLists(string $notes, string $date, string $upperCameName): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NOTES}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}'
        ];

        // 等待替换的内容
        $waitReplace = [
            $notes,
            $date,
            $upperCameName
        ];
        $templatePath = GenerateService::getTemplatePath('php/controller/listsController');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }

    /**
     * 处理创建方法
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @return string
     */
    private static function handleCreate(string $notes, string $date, string $upperCameName): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NOTES}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}'
        ];

        // 等待替换的内容
        $waitReplace = [
            $notes,
            $date,
            $upperCameName
        ];
        $templatePath = GenerateService::getTemplatePath('php/controller/createController');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }


    /**
     * 处理更新方法
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @return string
     */
    private static function handleUpdate(string $notes, string $date, string $upperCameName): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NOTES}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}'
        ];

        // 等待替换的内容
        $waitReplace = [
            $notes,
            $date,
            $upperCameName
        ];
        $templatePath = GenerateService::getTemplatePath('php/controller/updateController');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }


    /**
     * 处理删除方法
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @return string
     */
    private static function handleDelete(string $notes, string $date, string $upperCameName): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NOTES}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}'
        ];

        // 等待替换的内容
        $waitReplace = [
            $notes,
            $date,
            $upperCameName
        ];
        $templatePath = GenerateService::getTemplatePath('php/controller/deleteController');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }


    /**
     * 处理详情方法
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @return string
     */
    private static function handleDetail(string $notes, string $date, string $upperCameName): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NOTES}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}'
        ];

        // 等待替换的内容
        $waitReplace = [
            $notes,
            $date,
            $upperCameName
        ];
        $templatePath = GenerateService::getTemplatePath('php/controller/detailController');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }

}