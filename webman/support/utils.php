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