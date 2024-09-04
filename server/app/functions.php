<?php

/**
 * 生成密码hash值
 * @param string $password
 * @return string
 */
function encryption_hash(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}


/**
 * 隐藏手机号
 * @param string $mobile
 * @return string
 */
function hide_mobile(string $mobile): string
{
    return substr_replace($mobile, '****', 3, 4);
}


/**
 * 高精度 除法
 * @param $leftOperand
 * @param $rightOperand
 * @param int $scale
 * @return string|null
 */
function bcdiv_tools($leftOperand, $rightOperand, int $scale = 2): ?string
{
    return \bcdiv($leftOperand, $rightOperand, $scale);
}

/**
 * 高精度 乘法
 * @param $leftOperand
 * @param $rightOperand
 * @param int $scale
 * @return string
 */
function bcmul_tools($leftOperand, $rightOperand, int $scale = 2): string
{
    return \bcmul($leftOperand, $rightOperand, $scale);
}

/**
 * 高精度 加法
 * @param $leftOperand
 * @param $rightOperand
 * @param int $scale
 * @return string
 */
function bcadd_tools($leftOperand, $rightOperand, int $scale = 2): string
{
    return \bcadd($leftOperand, $rightOperand, $scale);
}


/**
 * 高精度 减法
 * @param $leftOperand
 * @param $rightOperand
 * @param int $scale
 * @return string
 */
function bcsub_tools($leftOperand, $rightOperand, int $scale = 2): string
{
    return \bcsub($leftOperand, $rightOperand, $scale);
}


/**
 * 下划线转驼峰
 * @param string $camelize_words
 * @param string $separator
 * @return string
 */
function camelize(string $camelize_words, string $separator = '_'): string
{
    $camelize_words = $separator . str_replace($separator, " ", strtolower($camelize_words));
    return ltrim(str_replace(" ", "", ucwords($camelize_words)), $separator);
}


/**
 * 驼峰转下划线
 * @param string $camelCaps
 * @param string $separator
 * @return string
 */
function uncamelize(string $camelCaps, string $separator = '_'): string
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}


/**
 * 时间戳转换日期
 * @param int|string $timeStamp 时间戳
 * @param bool $withTime 是否关联时间
 * @return string
 */
function format_time(int|string $timeStamp, bool $withTime = true): string
{
    $format = 'Y-m-d';
    $withTime && $format .= ' H:i:s';
    return $timeStamp ? date($format, $timeStamp) : '';
}

/**
 * 过滤emoji表情
 * @param $text
 * @return null|string|string[]
 */
function filter_emoji($text): array|string|null
{
    if (!is_string($text)) {
        return $text;
    }
    // 此处的preg_replace用于过滤emoji表情
    // 如需支持emoji表情, 需将mysql的编码改为utf8mb4
    return preg_replace('/[\xf0-\xf7].{3}/', '', $text);
}


/**
 * 根据指定长度截取字符串
 * @param $str
 * @param int $length
 * @return bool|string
 */
function str_substr($str, int $length = 30): bool|string
{
    if (strlen($str) > $length) {
        $str = mb_substr($str, 0, $length);
    }
    return $str;
}

/**
 * 文件夹不存在则创建
 * @param string $path
 * @return void
 */
function check_dir(string $path): void
{
    !is_dir(public_path() . $path) && mkdir(public_path() . $path, 0755, true);
}

/**
 * 获取token
 * @return string
 */
function get_token(): string
{
    return request()->header('Authorization') ?? '';
}

/**
 * TODO 为了适配以前之前埋的坑，暂时不处理（只是影响系统设置菜单接口）
 * 把数组key的小驼峰转化为下划线
 * @param $array
 * @return array
 */
function array_key_to_underline($array): array
{
    $newArray = [];
    foreach ($array as $key => $value) {
        $newArray[uncamelize($key)] = $value;
    }
    return $newArray;
}

/**
 * 格式化日志
 * @param mixed $value
 * @return bool|string
 */
function format_log(mixed $value): bool|string
{
    return is_string($value) ? $value : print_r($value, true);
}