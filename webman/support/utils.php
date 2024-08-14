<?php

use support\Response;

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
 * 返回封装后的 API 数据到客户端
 * @param array $data
 * @param int|null $code
 * @param string $message
 * @return Response
 */
function renderJson(array $data, int $code = null, string $message = ''): Response
{
    $response = [
        'code' => $code,
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
 * 根据指定路径创建文件夹
 * @param string $dirPath
 * @return string
 */
function mkdirPath(string $dirPath): string
{
    !is_dir($dirPath) && mkdir($dirPath, 0755, true);
    return $dirPath;
}

/**
 * 将字符串转换为字节
 * @param string $from
 * @return int|null
 */
function convertToBytes(string $from): ?int
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    $number = substr($from, 0, -2);
    $suffix = strtoupper(substr($from, -2));
    // B or no suffix
    if (is_numeric(substr($suffix, 0, 1))) {
        return preg_replace('/[^\d]/', '', $from);
    }
    $exponent = array_flip($units)[$suffix] ?? null;
    if ($exponent === null) {
        return null;
    }
    return $number * (1024 ** $exponent);
}


/**
 * 获取全局唯一标识符
 * @param bool $trim
 * @return string
 */
function guidV4(bool $trim = true): string
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
 * 获取数组指定列
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


/**
 * 多级线性结构处理成树形结构
 * @param array $data 线性结构数组
 * @param string $sub_key_name 二级结构数组的子级键名
 * @param string $id_name 数组id名
 * @param string $parent_id_name 数组祖先id名
 * @param int $parent_id 此值请勿给参数
 * @return array
 */
function linearToTree(array $data, string $sub_key_name = 'sub', string $id_name = 'id', string $parent_id_name = 'pid', int $parent_id = 0): array
{
    $tree = [];
    if (empty($data)) return $tree;
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
 * 从object中选取属性
 * @param $source
 * @param array $columns
 * @return array
 */
function getObjectPick($source, array $columns): array
{
    $dataset = [];
    if (empty($source)) return $dataset;
    foreach ($source as $key => $item) {
        in_array($key, $columns) && $dataset[$key] = $item;
    }
    return $dataset;
}

/**
 * 随机获取指定数量的数组元素
 * @param array $source 数组源
 * @param int $num 指定数量
 * @return array
 */
function getArrayRand(array $source, int $num): array
{
    if (count($source) < $num) {
        return [];
    }
    $keys = array_rand($source, $num);
    if (!is_array($keys)) {
        return [$source[$keys]];
    }
    $data = [];
    foreach ($keys as $key) {
        $data[] = $source[$key];
    }
    return $data;
}

/**
 * 数组转成key为指定字段的数组
 * @param $source
 * @param $index
 * @return array
 */
function arrayColumn2Key($source, $index): array
{
    $data = [];
    if (empty($source)) return $data;
    foreach ($source as $item) {
        $data[$item[$index]] = $item;
    }
    return $data;
}


/**
 * 格式化分页数据
 * @param $paginate
 * @return array
 */
function formattedPaginate($paginate): array
{
    return [
        'total' => $paginate->total(), // 获取总记录数
        'current_page' => $paginate->currentPage(), // 获取当前页码
        'per_page' => $paginate->perPage(), // 获取每页记录数
        'last_page' => $paginate->lastPage(), // 获取最后一页的页码
        'data' => $paginate->items(), // 获取当前页的数据
    ];
}