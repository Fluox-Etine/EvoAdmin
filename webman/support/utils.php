<?php

use support\Response;

/**
 * Here is your custom functions.
 */

/**
 * 返回封装后的 API 数据到客户端
 * @param array $data
 * @param int|null $code
 * @param string $message
 * @return Response
 */
function renderJson(array $data, int $code = null, string $message = ''): Response
{
    $response = [
        'status' => $code,
        'message' => $message,
    ];
    if (!empty($data)) {
        $response['data'] = $data;
    }
    return new Response(200, ['Content-Type' => 'application/json'], json_encode($response, JSON_UNESCAPED_UNICODE));
}

/**
 * 成功的返回
 * @param array|string $data
 * @param string $message
 * @return Response
 */
function renderSuccess(array|string $data = [], string $message = 'success'): Response
{
    if (is_string($data)) {
        $message = $data;
        $data = [];
    }
    return renderJson($data, 200, $message);
}

/**
 * 失败的返回
 * @param array|string $message
 * @param array $data
 * @return Response
 */
function renderError(array|string $message = 'error', array $data = []): Response
{
    if (is_array($message)) {
        $error = implode(',', $message);
    } else {
        $error = $message;
    }
    return renderJson($data, 500, $error);
}


/**
 * 设置默认的检索数据
 * @param array $query
 * @param array $default
 * @return array
 */
function setQueryDefaultValue(array $query, array $default = []): array
{
    $data = array_merge($default, $query);
    foreach ($query as $field => $value) {
        // 不存在默认值跳出循环
        if (!isset($default[$field])) continue;
        // 如果传参为空, 设置默认值
        if (empty($value) && $value !== '0') {
            $data[$field] = $default[$field];
        }
    }
    return $data;
}

/**
 * 获取全局唯一标识符
 * @param bool $trim
 * @return string
 */
function getGuidV4(bool $trim = true): string
{
    // Windows
    if (function_exists('com_create_guid') === true) {
        $chard = com_create_guid();
        return $trim ? trim($chard, '{}') : $chard;
    }
    // OSX/Linux
    if (function_exists('openssl_random_pseudo_bytes') === true) {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    // Fallback (PHP 4.2+)
    mt_srand(intval((double)microtime() * 10000));
    $chard = strtolower(md5(uniqid((string)rand(), true)));
    $hyphen = chr(45);                  // "-"
    $lbrace = $trim ? "" : chr(123);    // "{"
    $rbrace = $trim ? "" : chr(125);    // "}"
    return $lbrace .
        substr($chard, 0, 8) . $hyphen .
        substr($chard, 8, 4) . $hyphen .
        substr($chard, 12, 4) . $hyphen .
        substr($chard, 16, 4) . $hyphen .
        substr($chard, 20, 12) .
        $rbrace;
}


/**
 * 多级线性结构排序
 * 转换前：
 * [{"id":1,"pid":0,"name":"a"},{"id":2,"pid":0,"name":"b"},{"id":3,"pid":1,"name":"c"},
 * {"id":4,"pid":2,"name":"d"},{"id":5,"pid":4,"name":"e"},{"id":6,"pid":5,"name":"f"},
 * {"id":7,"pid":3,"name":"g"}]
 * 转换后：
 * [{"id":1,"pid":0,"name":"a","level":1},{"id":3,"pid":1,"name":"c","level":2},{"id":7,"pid":3,"name":"g","level":3},
 * {"id":2,"pid":0,"name":"b","level":1},{"id":4,"pid":2,"name":"d","level":2},{"id":5,"pid":4,"name":"e","level":3},
 * {"id":6,"pid":5,"name":"f","level":4}]
 * @param array $data 线性结构数组
 * @param string $sub_key_name 树子节点名
 * @param string $id_name 数组id名
 * @param string $parent_id_name 数组祖先id名
 * @param int $parent_id 此值请勿给参数
 * @return array
 */
function linearToTree(array $data, string $sub_key_name = 'sub', string $id_name = 'id', string $parent_id_name = 'pid', int $parent_id = 0): array
{
    if (empty($data)) {
        return [];
    }
    $tree = [];
    foreach ($data as $row) {
        if ($row[$parent_id_name] == $parent_id) {
            $temp = $row;
            $child = linearToTree($data, $sub_key_name, $id_name, $parent_id_name, $row[$id_name]);
            if ($child) {
                $temp[$sub_key_name] = $child;
            }
            $tree[] = $temp;
        }
    }
    return $tree;
}

/**
 * 生成密码hash值
 * @param string $password
 * @return string
 */
function encryptionHash(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * 数组转为json
 * @param $data
 * @param int $options
 * @return false|string
 */
function jsonEncode($data, int $options = JSON_UNESCAPED_UNICODE): bool|string
{
    return json_encode($data, $options);
}

/**
 * json转义为数组
 * @param $json
 * @return array|mixed
 */
function jsonDecode($json): mixed
{
    return json_decode($json, true);
}

/**
 * 获取数组中指定的列
 * @param $source
 * @param $column
 * @return array
 */
function getArrayColumn($source, $column): array
{
    $columnArr = [];
    foreach ($source as $item) {
        isset($item[$column]) && $columnArr[] = $item[$column];
    }
    return $columnArr;
}