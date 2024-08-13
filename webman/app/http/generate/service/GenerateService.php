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
            // 时间
            $date = date('Y/m/d H:i');
            // 类注释
            $classComment = $params['class_comment'];
            // 包名
            $packageName = self::getPackageNameContent($classDir);

            $tmpController = ControllerService::handleController([
                'moduleName' => $moduleName,
                'classDir' => $classDir,
                'tableName' => $tableName,
                'upperCameName' => $upperCameName,
                'classComment' => $classComment,
                'packageName' => $packageName,
            ]);
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


    /**
     * 获取包名
     * @param string $classDir
     * @return string
     */
    private static function getPackageNameContent(string $classDir): string
    {
        return !empty($classDir) ? '\\' . $classDir : '';
    }


    /**
     * 获取模板路径
     * @param string $templateName
     * @return string
     */
    public static function getTemplatePath(string $templateName): string
    {
        return config('env.generate.template_dir') . $templateName . '.stub';
    }


    /**
     * 获取命名空间内容
     * @param string $moduleName // 模块名
     * @param string $classDir // 类目录
     * @param string $component
     * @return string
     */
    public static function getNameSpaceContent(string $moduleName, string $classDir, string $component): string
    {
        $lowerComponent = strtolower($component);
        if (empty($classDir)) {
            return "namespace app\\http\\$moduleName\\$lowerComponent;";
        }
        $ucfComponent = ucfirst($component);
        return "namespace app\\http\\$moduleName\\{$lowerComponent}\\" . $classDir . $ucfComponent . ";";
    }


    /**
     * 替换文件数据
     * @param array $needReplace
     * @param array $waitReplace
     * @param string $template
     * @return array|false|string|string[]
     */
    public static function replaceFileData(array $needReplace, array $waitReplace, string $template): array|bool|string
    {
        return str_replace($needReplace, $waitReplace, file_get_contents($template));
    }
}