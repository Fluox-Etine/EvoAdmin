<?php

namespace app\http\generate\service;

class ModelService
{

    /**
     * 生成模型
     * @param array $params
     * @return string
     */
    public static function handleModel(array $params): string
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
            '{DELETE_USE}',
            '{TABLE_NAME}',
            '{PRIMARY_KEY}'
        ];
        $deleteUse = '';
        if ($params['deleteType'] === 1) {
            // 2 软删除
            $deleteUse = 'use SoftDeletes;' . PHP_EOL;
        }
        // 等待替换的内容
        $waitReplace = [
            self::getNameSpaceContent($params['classDir']),
            self::getUserContent($params['deleteType']),
            $params['classComment'].'模型类',
            $params['date'],
            $params['upperCameName'],
            $params['moduleName'],
            $params['packageName'],
            $deleteUse,
            $params['tableName'],
            $params['PK']
        ];
        $templatePath = GenerateService::getTemplatePath('php/model');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }


    /**
     * 获取命名空间内容
     * @param string $classDir
     * @return string
     */
    private static function getNameSpaceContent(string $classDir): string
    {
        if (empty($classDir)) {
            return "namespace app\\common\\model;";
        }
        return "namespace app\\common\\model\\" . $classDir . ";";
    }


    /**
     * 获取删除类型内容
     * @param int $deleteType
     * @return string
     */
    private static function getUserContent(int $deleteType): string
    {
        $content = '';
        if ($deleteType == 1) {
            $content .= 'use Illuminate\Database\Eloquent\SoftDeletes;' . PHP_EOL;
        }
        return $content;
    }
}