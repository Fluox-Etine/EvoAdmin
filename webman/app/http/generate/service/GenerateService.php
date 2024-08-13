<?php

namespace app\http\generate\service;

class GenerateService
{

    public static function generate(array $params)
    {
        try {
            // 模块名
            $moduleName = strtolower($params['module_name']);
            // 类目录
            $classDir = $params['class_dir'];
            // 数据库表名
            $tableName = self::getNoPrefixTableName($params['table_name']);
            // 包名
            $upperCameName = self::underscoreToCamelCase($tableName);
        } catch (\Throwable $e) {
            var_dump('GenerateService===getMessage' . $e->getMessage());
            var_dump('GenerateService===getFile' . $e->getFile());
            var_dump('GenerateService===getLine' . $e->getLine());
            return renderError($e->getMessage());
        }
    }


    /**
     * 获取无前缀数据表名
     * @param string $tableName
     * @return string
     */
    private static function getNoPrefixTableName(string $tableName): string
    {
        $prefix = config('database.connections.mysql.prefix');
        $prefixIndex = strpos($tableName, $prefix);
        if ($prefixIndex === false || $prefixIndex !== 0) {
            return $tableName;
        }
        return trim(str_replace($prefix, '', $tableName));
    }


    /**
     * 下划线转驼峰(首字母大写)
     * @param string $underscoreName
     * @param bool $firstCharacterUpper
     * @return string
     */
    private static function underscoreToCamelCase(string $underscoreName, bool $firstCharacterUpper = true): string
    {
        // 将下划线命名法转换为数组
        $parts = explode('_', $underscoreName);

        // 处理每个部分，使其首字母大写
        $parts = array_map('ucfirst', $parts);

        // 将数组合并为字符串
        $result = implode('', $parts);

        // 如果需要首字母大写，转换第一个字符
        if ($firstCharacterUpper) {
            $result[0] = strtoupper($result[0]);
        }

        return $result;
    }
}