<?php

namespace app\http\generate\service\console;

use app\http\generate\service\GenerateService;

class ColumnsService
{

    /**
     * 生成字段文件
     * @param array $params
     * @return string
     */
    public static function handleColumns(array $params): string
    {
        // 需要替换的变量
        $needReplace = [
            '{COLUMNS}'
        ];

        // 等待替换的内容
        $waitReplace = [
            self::handleColumn($params['fields'])
        ];
        return GenerateService::replaceFileData($needReplace, $waitReplace, GenerateService::getTemplatePath('vue/DynamicTable/columns'));
    }


    /**
     * 生成字段
     * @param array $fields
     * @return string
     */
    private static function handleColumn(array $fields): string
    {
        $str = '';
        foreach ($fields as $field) {
            if ($field['LIST']) {
                $title = $field['COLUMN_COMMENT'];
                if(empty($title)){
                    $title = $field['COLUMN_NAME'];
                }
                // 判断是否为创建和修改时间
                if ($field['COLUMN_NAME'] == 'created_at' || $field['COLUMN_NAME'] == 'updated_at') {
                    $str .= vsprintf(
                        "  {\n" .
                        "    title: '%s',\n" .
                        "    dataIndex: '%s',\n" .
                        "    hideInSearch: %s,\n" .
                        "    customRender: ({ record }) => {\n" .
                        "      return formatToDateTime(record.%s);\n" .
                        "    },\n" .
                        "  },\n",
                        [
                            $title,
                            $field['COLUMN_NAME'],
                            $field['FILTER'] ? 'false' : 'true',
                            $field['COLUMN_NAME']
                        ]
                    );
                } else {
                    $str .= vsprintf(
                        "  {\n" .
                        "    title: '%s',\n" .
                        "    dataIndex: '%s',\n" .
                        "    hideInSearch: %s,\n" .
                        "  },\n",
                        [
                            $title,
                            $field['COLUMN_NAME'],
                            $field['FILTER'] ? 'false' : 'true',
                        ]
                    );
                }
            }
        }
        return $str;
    }
}