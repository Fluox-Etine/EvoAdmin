<?php

namespace app\http\generate\service;

class ValidateService
{

    /**
     * 生成验证类
     * @param array $params
     * @return string
     */
    public static function handleValidate(array $params): string
    {
        // 需要替换的变量
        $needReplace = [
            '{NAMESPACE}',
            '{CLASS_COMMENT}',
            '{DATE}',
            '{UPPER_CAMEL_NAME}',
            '{MODULE_NAME}',
            '{PACKAGE_NAME}',
            '{FUNCTIONS}'
        ];
        // 等待替换的内容
        $waitReplace = [
            GenerateService::getNameSpaceContent($params['moduleName'], $params['classDir'], $params['upperCameName'], 'validate'),
            $params['classComment'] . '验证器类',
            $params['date'],
            $params['upperCameName'],
            $params['moduleName'],
            $params['packageName'],
            self::handleFunctions($params['validate'], $params['fields'], $params['date'])
        ];
        $templatePath = GenerateService::getTemplatePath('php/validate');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }


    /**
     * 处理函数
     * @param array $action
     * @param array $fields
     * @param string $date
     * @return string
     */
    private static function handleFunctions(array $action, array $fields, string $date): string
    {
        $content = '';
        //判断是否开启添加验证
        if (isset($action['create']) && $action['create']) {
            var_dump(12);
            $content .= self::handleCreateValidate($fields, $date) . PHP_EOL;
        }
        if (isset($action['update']) && $action['update']) {
            var_dump(22);
            $content .= self::handleUpdateValidate($fields, $date) . PHP_EOL;
        }
        return $content;
    }

    /**
     * 创建验证规则
     * @param array $fields
     * @param string $date
     * @return string
     */
    private static function handleCreateValidate(array $fields, string $date): string
    {
        // 需要替换的变量
        $needReplace = [
            '{DATE}',
            '{CREATE_VALIDATE}',
        ];
        $validate = self::getFormatValidate($fields);
        $waitReplace = [
            $date,
            $validate
        ];
        $templatePath = GenerateService::getTemplatePath('php/validate/createValidate');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }

    /**
     * 更新验证规则
     * @param array $fields
     * @param string $date
     * @return string
     */
    private static function handleUpdateValidate(array $fields, string $date): string
    {
        // 需要替换的变量
        $needReplace = [
            '{DATE}',
            '{UPDATE_VALIDATE}',
        ];
        $validate = self::getFormatValidate($fields);
        $waitReplace = [
            $date,
            $validate
        ];
        $templatePath = GenerateService::getTemplatePath('php/validate/updateValidate');
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }

    /**
     * 格式化验证规则
     * @param array $fields
     * @return string
     */
    private static function getFormatValidate(array $fields): string
    {
        if (empty($fields)) {
            return '';
        }
        $str = '';
        foreach ($fields as $key => $field) {
            // 判断当前字段是否需要 验证
            if (!empty($field['VALIDATE'])) {
                // 内层循环数组\
                $setName = $field['COLUMN_COMMENT'] ?? $field['COLUMN_NAME'];
                $validateRuleBase = "'" . $field['COLUMN_NAME'] . "'" . ' => v::';
                $validateRule = '';
                foreach ($field['VALIDATE'] as $validate) {
                    switch ($validate) {
                        // 必填项校验
                        case 1:
//                            $str .= '// 必填项校验' . PHP_EOL;
                            $validateRule .= 'NotEmpty()->';
                            break;
                        case 2:
//                            $str .= '// 必填长度校验' . PHP_EOL;
                            $maxLength = $field['CHARACTER_MAXIMUM_LENGTH'] ?? 11;
                            $validateRule .= 'length(1, ' . $maxLength . ')->';
                            break;
                        case 3:
//                            $str .= '// 数字校验' . PHP_EOL;
                            $validateRule .= 'Number()->';
                            break;
                        case 4:
//                            $str .= '// 字符串校验' . PHP_EOL;
                            $validateRule .= 'stringType()->';
                            break;
                        case 5:
//                            $str .= '// 邮箱地址校验' . PHP_EOL;
                            $validateRule .= 'email()->';
                            break;
                        case 6:
//                            $str .= '// 手机号码校验' . PHP_EOL;
                            $validateRule .= 'phone()->';
                            break;
                        case 7:
//                            $str .= '// 验证是否整数' . PHP_EOL;
                            $validateRule .= 'IntType()->';
                            break;
                        case 8:
//                            $str .= '// 验证字母' . PHP_EOL;
                            $validateRule .= 'Alpha()->';
                            break;
                        case 9:
//                            $str .= '// 验证是否为IP地址' . PHP_EOL;
                            $validateRule .= 'ip()->';
                            break;
                        default:
                            $validateRule .= '';
                    }
                }
                if (!empty($validateRule)) {
                    $str .= $validateRuleBase . $validateRule . 'setName(\'' . $setName . '\'),' . PHP_EOL;
                }
            }
        }
        if (!empty($str)) {
            $str = substr($str, 0, -2);
            $str = GenerateService::setBlankSpace($str, "                ");
        }
        return $str;
    }
}