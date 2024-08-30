<?php

namespace app\http\generate\service\console;

use app\http\generate\service\GenerateService;

class RequestService
{

    /**
     * 生成请求文件
     * @param array $params
     * @return array
     */
    public static function handleRequest(array $params): array
    {

        $action = [
            'list' => [],
            'create' => [],
            'update' => [],
            'detail' => [],
        ];
        foreach ($params['fields'] as $field) {
            if ($field['CREATE']) $action['create'][] = $field;
            if ($field['UPDATE']) $action['update'][] = $field;
            if ($field['DETAIL']) $action['detail'][] = $field;
            if ($field['QUERY_TYPE']) $action['list'][] = $field;
        }
        if (empty($action['update'])) {
            $action['update'][] = [
                'COLUMN_NAME' => $params['pk'],
                'COLUMN_COMMENT' => '主键',
                'DATA_TYPE' => 'int',
            ];
        }
        $isPk = false;
        foreach ($action['update'] as $item) {
            if ($item['COLUMN_KEY'] == "PRI") {
                $isPk = true;
            }
        }
        if (!$isPk) {
            $action['update'][] = [
                'COLUMN_NAME' => $params['pk'],
                'COLUMN_COMMENT' => '主键',
                'DATA_TYPE' => 'int',
            ];
        }
        $request = self::handleRequestFunctions(!empty($action['list']), $params['upperCameName'], $params['classComment'], $params['gen']);
        $types = self::handleTypes($action, $params['upperCameName'], $params['gen']);
        return [
            'request' => $request,
            'types' => $types
        ];
    }


    /**
     * 生成请求文件方法
     * @param bool $query
     * @param string $upperCameName
     * @param string $classComment
     * @param array $gen
     * @return string
     */
    protected static function handleRequestFunctions(bool $query, string $upperCameName, string $classComment, array $gen): string
    {
        // 需要替换的变量
        $needReplace = [
            '{FUNCTIONS}'
        ];

        $str = '';
        $upperCameNameArray = explode('_', uncamelize($upperCameName));
        $path = '';
        foreach ($upperCameNameArray as $value) {
            $path .= '/' . $value;
        }
        $function = ['list' => '列表', 'create' => '创建', 'update' => '更新', 'deleted' => '删除', 'detail' => '详情'];
        foreach ($function as $key => $value) {
            if (empty($gen[$key])) continue;
            $endpoint = strtolower($key);
            $bodyRequest = ' body: API.' . $upperCameName . ucfirst($key) . 'Dto, ';
            if ($key != 'list') {
                $text = '操作成功';
                $successMessageKey = ' successMsg:' . "'" . $text . "' ";
                if ($key == 'deleted' || $key == 'detail') {
                    $bodyRequest = ' body: API.QueryId, ';
                }
            } else {
                $successMessageKey = '';
                if (!$query) {
                    $bodyRequest = '';
                }
            }
            $bodyData = '';
            if ($bodyRequest) {
                $bodyData = 'data: body,';
            }
            // 格式化字符串
            $str .= vsprintf(
                "/** %s POST %s */\n" .
                "export async function %s(%soptions?: RequestOptions) {\n" .
                "    return request<%s>('%s', {\n" .
                "        method: 'POST',\n" .
                "        headers: {\n" .
                "            'Content-Type': 'application/json',\n" .
                "        },\n" .
                "       %s\n" .
                "        ...(options || {%s}),\n" .
                "    });\n" .
                "}",
                [
                    $classComment . ' ' . $value . '方法', // 替换 {CLASS_COMMENT}
                    $path, // 替换 {PATH}
                    $endpoint,
                    $bodyRequest, // 替换 {BODY}
                    'any', // request<%s>
                    $path, // 替换 {PATH} 再次出现
                    $bodyData,
                    $successMessageKey, // 替换 SUCCESS_MSG 键
                ]
            );
            $str .= PHP_EOL;
            $str .= PHP_EOL;
        }

        // 等待替换的内容
        $waitReplace = [
            $str
        ];
        return GenerateService::replaceFileData($needReplace, $waitReplace, GenerateService::getTemplatePath('vue/api/request'));
    }


    /**
     * 生成请求文件类型
     * @param array $files
     * @param string $upperCameName
     * @param array $gen
     * @return string
     */
    private static function handleTypes(array $files, string $upperCameName, array $gen)
    {
        // 需要替换的变量
        $needReplace = [
            '{TYPES}'
        ];
        $str = '';
        $function = ['list' => '列表', 'create' => '创建', 'update' => '更新', 'detail' => '详情'];
        foreach ($function as $key => $value) {
            if (empty($gen[$key]) || empty($files[$key])) continue;
            $str .= '   /** ' . $value . '参数 */' . PHP_EOL;
            $str .= '   type ' . $upperCameName . ucfirst($key) . 'Dto = {' . PHP_EOL;
            foreach ($files[$key] as $file) {
                $types = 'string';
                if ($file['DATA_TYPE'] == 'int' || $file['DATA_TYPE'] == 'bigint' || $file['DATA_TYPE'] == 'tinyint' || $file['DATA_TYPE'] == 'smallint') {
                    $types = 'number';
                } elseif ($file['DATA_TYPE'] == 'float' || $file['DATA_TYPE'] == 'double' || $file['DATA_TYPE'] == 'decimal') {
                    $types = 'float';
                }
                $str .= vsprintf(
                    "        /** %s */\n" .
                    "        %s?: %s;\n",
                    [
                        $file['COLUMN_COMMENT'], // 替换 {COMMENT}
                        $file['COLUMN_NAME'], // 替换 {FILE}
                        $types
                    ]
                );
            }
            $str .= '   };' . PHP_EOL;
            $str .= PHP_EOL;
        }
        $str = substr($str, 0, -2);

        // 等待替换的内容
        $waitReplace = [
            $str
        ];
        return GenerateService::replaceFileData($needReplace, $waitReplace, GenerateService::getTemplatePath('vue/api/types'));
    }
}