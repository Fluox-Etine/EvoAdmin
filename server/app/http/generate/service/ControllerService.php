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
            self::getUseContent($params['moduleName'], $params['classDir'], $params['upperCameName'], $params['gen']),
            $params['classComment'] . '控制器类',
            $params['date'],
            GenerateService::getLastCamelCaseWord($params['upperCameName'])[0],
            $params['moduleName'],
            $params['packageName'],
            self::handleFunctions($params['gen'], $params['classComment'], $params['date'], $params['upperCameName'])
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
     * @param array $gen
     * @return string
     */
    private static function getUseContent(string $moduleName, string $classDir, string $upperCameName, array $gen): string
    {
        $isValidate = false;
        // 判断是否有验证器
        if ($gen['validate']['create'] || $gen['validate']['update']) {
            $isValidate = true;
        }
        $upperCameNameArray = GenerateService::getLastCamelCaseWord($upperCameName);
        if ($upperCameNameArray[1] === 1) {
            $upperCameNameLogicStr = $upperCameNameArray[0] . "Logic;";
            $upperCameNameValidateStr = $upperCameName . "Validate;";
        } else {
            $upperCameNameLogicStr = $upperCameNameArray[0] . "Logic as " . $upperCameName . "Logic;";
            $upperCameNameValidateStr = $upperCameNameArray[0] . "Validate as " . $upperCameName . "Validate;";
        }
        $tpl = "use app\\http\\$moduleName\\logic\\" . $upperCameNameLogicStr;
        if ($isValidate) {
            $tpl .= PHP_EOL . "use app\\http\\validate\\" . $upperCameNameValidateStr;
        }
        if (!empty($classDir)) {
            $tpl = "use app\\http\\$moduleName\\logic\\" . $classDir . "\\" . $upperCameNameLogicStr;
            if ($isValidate) {
                $tpl .= PHP_EOL . "use app\\http\\$moduleName\\validate\\" . $classDir . "\\" . $upperCameNameValidateStr;
            }
        }
        return $tpl;
    }

    /**
     * 处理方法
     * @param array $gen
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @return string
     */
    private static function handleFunctions(array $gen, string $notes, string $date, string $upperCameName): string
    {
        $content = '';
        $methods = [
            'list' => 'handleLists',
            'create' => 'handleCreate',
            'update' => 'handleUpdate',
            'delete' => 'handleDelete',
            'detail' => 'handleDetail', // 假设存在 handleDetail 方法
        ];
        foreach ($methods as $action => $methodName) {
            if (isset($gen['controller'][$action]) && $gen['controller'][$action]) {
                $content .= self::$methodName($notes, $date, $upperCameName, $gen['validate']) . PHP_EOL;
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
     * @param array $validate
     * @return string
     */
    private static function handleCreate(string $notes, string $date, string $upperCameName, array $validate): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NOTES}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}',
            '{VALIDATE}'
        ];

        $validateStr = '';
        if ($validate['create']) {
            $validateStr = PHP_EOL . $upperCameName . 'Validate::createValidate($params);';
            $validateStr = GenerateService::setBlankSpace($validateStr, "        ");
        }
        // 等待替换的内容
        $waitReplace = [
            $notes,
            $date,
            $upperCameName,
            $validateStr
        ];
        $templatePath = GenerateService::getTemplatePath('php/controller/createController');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }


    /**
     * 处理更新方法
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @param array $validate
     * @return string
     */
    private static function handleUpdate(string $notes, string $date, string $upperCameName, array $validate): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NOTES}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}',
            '{VALIDATE}'
        ];

        $validateStr = '';
        if ($validate['create']) {
            $validateStr = PHP_EOL . $upperCameName . 'Validate::updateValidate($params);';
            $validateStr = GenerateService::setBlankSpace($validateStr, "        ");
        }
        // 等待替换的内容
        $waitReplace = [
            $notes,
            $date,
            $upperCameName,
            $validateStr
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