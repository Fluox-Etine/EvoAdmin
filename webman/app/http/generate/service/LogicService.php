<?php

namespace app\http\generate\service;

class LogicService
{

    public static function handleLogic(array $params): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NAMESPACE}',
            '{USE}',
            '{CLASS_COMMENT}',
            '{UPPER_CAMEL_NAME}',
            '{MODULE_NAME}',
            '{PACKAGE_NAME}',
            '{FUNCTIONS}'
        ];

        // 等待替换的内容
        $waitReplace = [
            GenerateService::getNameSpaceContent($params['moduleName'], $params['classDir'], 'logic'),
            self::getUseContent($params['classDir'], $params['upperCameName']),
            $params['classComment'],
            $params['upperCameName'],
            $params['moduleName'],
            $params['packageName'],
            self::handleFunctions($params['methods'], $params['classComment'], $params['date'], $params['upperCameName'])
        ];

        $templatePath = GenerateService::getTemplatePath('php/logic');
        // 替换内容
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }


    /**
     * 获取use内容
     * @param string $classDir
     * @param string $tableName
     * @return string
     */
    private static function getUseContent(string $classDir, string $tableName): string
    {
        if (empty($classDir)) {
            $tpl = "use app\\common\\model\\" . $classDir . "Model;";
        } else {
            $tpl = "use app\\common\\model\\" . $classDir . "\\" . $tableName . "Model;";
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
    private static function handleFunctions(array $method, string $notes, string $date, string $upperCameName, string $pk): string
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
                $content .= self::$methodName($notes, $date, $upperCameName, $pk) . PHP_EOL;
            }
        }
        return $content;
    }

    private static function handleLists(string $notes, string $date, string $upperCameName): string
    {

    }

    private static function handleCreate(string $notes, string $date, string $upperCameName): string
    {

    }

    private static function handleUpdate(string $notes, string $date, string $upperCameName): string
    {

    }


    /**
     * 处理删除方法
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @param string $pk
     * @return string
     */
    private static function handleDelete(string $notes, string $date, string $upperCameName, string $pk): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NOTES}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}',
            '{PK}'
        ];
        // 等待替换的内容
        $waitReplace = [
            $notes,
            $date,
            $upperCameName,
            $pk
        ];
        $templatePath = GenerateService::getTemplatePath('php/controller/deleteLogic');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }


    /**
     * 处理详情方法
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @param $pk
     * @param array $fields
     * @return string
     */
    private static function handleDetail(string $notes, string $date, string $upperCameName, $pk, array $fields): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NOTES}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}',
            '{PK}',
            '{FIELDS}'
        ];
        // 等待替换的内容
        $waitReplace = [
            $notes,
            $date,
            $upperCameName,
            $pk,
            $fields
        ];
        $templatePath = GenerateService::getTemplatePath('php/controller/detailLogic');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }
}