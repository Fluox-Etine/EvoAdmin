<?php

namespace app\http\generate\service\console;

use app\http\generate\service\GenerateService;

class FormSchemasService
{

    /**
     * 生成表单
     * @param array $params
     * @return string
     */
    public static function handleFormSchemas(array $params): string
    {
        // 需要替换的变量
        $needReplace = [
            '{COLUMNS}'
        ];

        // 等待替换的内容
        $waitReplace = [
            self::handleColumn($params['fields'], $params['pk'])
        ];
        return GenerateService::replaceFileData($needReplace, $waitReplace, GenerateService::getTemplatePath('vue/DynamicTable/formSchemas'));
    }

    /**
     * 生成表单
     * @param array $files
     * @param string $pk
     * @return string
     */
    private static function handleColumn(array $files,string $pk): string
    {
        $str = '';
        foreach ($files as $file) {
            if ($file['CREATE'] && $file['COLUMN_KEY'] != 'PRI' && $file['UPDATE']) {
                $str .= vsprintf(
                    "  {\n" .
                    "    field: '%s',\n" .
                    "    component: 'Input',\n" .
                    "    label: '%s',\n" .
                    "    rules: [{ required: true }],\n" .
                    "    colProps: {\n" .
                    "      span: 12,\n" .
                    "    },\n" .
                    "  },\n",
                    [
                        $file['COLUMN_NAME'], // 替换 {FILE}
                        $file['COLUMN_COMMENT'], // 替换 {TYPE}
                    ]
                );
            } elseif ($file['CREATE'] && $file['COLUMN_KEY'] != 'PRI' && !$file['UPDATE']) {
                $str .= vsprintf(
                    "  {\n" .
                    "    field: '%s',\n" .
                    "    component: 'Input',\n" .
                    "    label: '%s',\n" .
                    "    rules: [{ required: true }],\n" .
                    "    vIf: ({formModel}) => !formModel['%s'],\n" .
                    "    colProps: {\n" .
                    "      span: 12,\n" .
                    "    },\n" .
                    "  },\n",
                    [
                        $file['COLUMN_NAME'], // 替换 {FILE}
                        $file['COLUMN_COMMENT'], // 替换 {TYPE}
                        $pk
                    ]
                );
            } elseif (!$file['CREATE'] && $file['COLUMN_KEY'] == 'PRI' && $file['UPDATE']) {
                $str .= vsprintf(
                    "  {\n" .
                    "    field: '%s',\n" .
                    "    component: 'Input',\n" .
                    "    label: '%s',\n" .
                    "    rules: [{ required: true }],\n" .
                    "    vIf: ({formModel}) => formModel['%s'],\n" .
                    "    colProps: {\n" .
                    "      span: 12,\n" .
                    "    },\n" .
                    "  },\n",
                    [
                        $file['COLUMN_NAME'], // 替换 {FILE}
                        $file['COLUMN_COMMENT'], // 替换 {TYPE}
                        $pk
                    ]
                );
            }
        }
        return $str;
    }
}