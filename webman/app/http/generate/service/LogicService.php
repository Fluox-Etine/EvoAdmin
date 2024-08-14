<?php

namespace app\http\generate\service;

class LogicService
{

    /**
     * 生成逻辑层
     * @param array $params
     * @return string
     */
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
            self::handleFunctions($params['methods'], $params['classComment'], $params['date'], $params['upperCameName'], $params['pk'], $params['queryColumn'], $params['createColumn'], $params['updateColumn'], $params['fields'])
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
     * @param string $pk
     * @param array $queryColumn
     * @param array $createColumn
     * @param array $updateColumn
     * @param array $fields
     * @return string
     */
    private static function handleFunctions(array $method, string $notes, string $date, string $upperCameName, string $pk, array $queryColumn, array $createColumn, array $updateColumn, array $fields): string
    {
        $content = '';
        if ($method['lists']) {
            $content .= self::handleLists($notes, $date, $upperCameName, $queryColumn);
        }
        if ($method['create']) {
            $content .= self::handleCreate($notes, $date, $upperCameName, $createColumn);
        }
        if ($method['update']) {
            $content .= self::handleUpdate($notes, $date, $upperCameName, $pk, $updateColumn);
        }
        if ($method['detail']) {
            $content .= self::handleDetail($notes, $date, $upperCameName, $pk, $fields);
        }
        if ($method['delete']) {
            $content .= self::handleDelete($notes, $date, $upperCameName, $pk);
        }
        return $content;
    }

    /**
     * 处理列表方法
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @param array $queryColumn
     * @return string
     */
    private static function handleLists(string $notes, string $date, string $upperCameName, array $queryColumn): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NOTES}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}',
            'QUERY_CONDITION'
        ];
        $queryCondition = self::getFormatQueryContent($queryColumn);
        // 等待替换的内容
        $waitReplace = [
            $notes,
            $date,
            $upperCameName,
            $queryCondition
        ];
        $templatePath = GenerateService::getTemplatePath('php/logic/listsLogic');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }

    /**
     * 处理创建方法
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @param array $createColumn
     * @return string
     */
    private static function handleCreate(string $notes, string $date, string $upperCameName, array $createColumn): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NOTES}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}',
            'CREATE_DATA'
        ];
        $updateData = self::getFormatDataContent($createColumn, 'create');
        // 等待替换的内容
        $waitReplace = [
            $notes,
            $date,
            $upperCameName,
            $updateData
        ];
        $templatePath = GenerateService::getTemplatePath('php/logic/createLogic');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }

    /**
     * 处理更新方法
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @param string $pk
     * @param array $updateColumn
     * @return string
     */
    private static function handleUpdate(string $notes, string $date, string $upperCameName, string $pk, array $updateColumn): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NOTES}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}',
            '{PK}',
            'UPDATE_DATA'
        ];
        $createData = self::getFormatDataContent($updateColumn);
        // 等待替换的内容
        $waitReplace = [
            $notes,
            $date,
            $upperCameName,
            $pk,
            $createData
        ];
        $templatePath = GenerateService::getTemplatePath('php/logic/updateLogic');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
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
        $templatePath = GenerateService::getTemplatePath('php/logic/deleteLogic');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }


    /**
     * 处理详情方法
     * @param string $notes
     * @param string $date
     * @param string $upperCameName
     * @param string $pk
     * @param array $fields
     * @return string
     */
    private static function handleDetail(string $notes, string $date, string $upperCameName, string $pk, array $fields): string
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
        $templatePath = GenerateService::getTemplatePath('php/logic/detailLogic');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }


    /**
     *
     * @param array $tableColumn
     * @param string $flag
     * @return string
     */
    private static function getFormatDataContent(array $tableColumn, string $flag = ''): string
    {
        if (empty($tableColumn)) {
            return '';
        }
        $content = '';
        foreach ($tableColumn as $column) {
            $content .= self::formatColumn($column);
        }
        if (empty($content)) {
            return $content;
        }
        if ($flag == 'create') {
            $content .= "'created_at' => " . time() . ',' . PHP_EOL;
        }
        $content .= "'updated_at' => " . time();
        $content = substr($content, 0, -2);
        return GenerateService::setBlankSpace($content, "                ");
    }

    /**
     * 格式化字段
     * @param array $column
     * @return string
     */
    private static function formatColumn(array $column): string
    {
        if ($column['column_type'] == 'int' && $column['view_type'] == 'datetime') {
            // 物理类型为int，显示类型选择日期的情况
            $content = "'" . $column['column_name'] . "' => " . 'strtotime($params[' . "'" . $column['column_name'] . "'" . ']),' . PHP_EOL;
        } else {
            $content = "'" . $column['column_name'] . "' => " . '$params[' . "'" . $column['column_name'] . "'" . '],' . PHP_EOL;
        }
        return $content;
    }


    /**
     * 获取查询条件
     * @param array $tableColumn
     * @return string
     */
    private static function getFormatQueryContent(array $tableColumn): string
    {
        if (empty($tableColumn)) {
            return '';
        }
        $content = '';
        foreach ($tableColumn as $column) {
            $content .= $content = "'" . $column['column_name'] . "' => " . null . ',' . PHP_EOL;
        }
        if (empty($content)) {
            return $content;
        }
        $content = substr($content, 0, -2);
        return GenerateService::setBlankSpace($content, "                ");
    }
}