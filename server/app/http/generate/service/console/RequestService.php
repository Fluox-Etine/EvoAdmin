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
            'query' => []
        ];
        foreach ($params['fields'] as $field) {
            if ($field['LIST']) $action['list'][] = $field;
            if ($field['CREATE']) $action['create'][] = $field;
            if ($field['UPDATE']) $action['update'][] = $field;
            if ($field['DETAIL']) $action['detail'][] = $field;
            if ($field['QUERY_TYPE']) $action['query'][] = $field;
        }

        $request = self::handleRequestFunctions(!empty($action['query']), $params['upperCameName'], $params['classComment'], $params['gen']);
        return [
            'request' => $request,
            'types' => ''
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
}