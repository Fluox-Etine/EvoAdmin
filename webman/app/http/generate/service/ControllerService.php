<?php

namespace app\http\generate\service;

class ControllerService
{

    public static function handleController(array $params)
    {
        // 需要替换的变量
        $needReplace = [
            '{NAMESPACE}',
            '{USE}',
            '{CLASS_COMMENT}',
            '{UPPER_CAMEL_NAME}',
            '{MODULE_NAME}',
            '{PACKAGE_NAME}',
        ];

        // 等待替换的内容
        $waitReplace = [
            GenerateService::getNameSpaceContent($params['moduleName'], $params['classDir'], 'controller'),
            self::getUseContent($params['moduleName'], $params['classDir'], $params['upperCameName']),
            $params['classComment'],
            $params['upperCameName'],
            $params['moduleName'],
            $params['packageName'],
        ];

        $templatePath = GenerateService::getTemplatePath('php/controller');

        // 替换内容
        $content = GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
        return true;
    }


    /**
     * 获取use内容
     * @param string $moduleName
     * @param string $classDir
     * @param string $tableName
     * @return string
     */
    private static function getUseContent(string $moduleName, string $classDir, string $tableName): string
    {
        if (empty($classDir)) {
            $tpl = "use app\\http\\$moduleName\\logic\\" . $classDir . "Logic;";
        } else {
            $tpl = "use app\\http\\$moduleName\\logic\\" . $classDir . "\\" . $tableName . "Logic;";
        }
        return $tpl;
    }
}