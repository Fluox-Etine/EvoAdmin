<?php

namespace app\http\generate\service;

class ControllerService
{


    /**
     * 获取命名空间内容
     * @param string $moduleName // 模块名
     * @param string $classDir // 类目录
     * @return string
     */
    private static function getNameSpaceContent(string $moduleName, string $classDir): string
    {
        if (empty($classDir)) {
            return "namespace app\\http\\$moduleName\\controller;";
        }
        return "namespace app\\http\\$moduleName\\controller\\" . $classDir . "Controller;";
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