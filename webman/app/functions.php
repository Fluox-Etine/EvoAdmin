<?php


/**
 * 获取全局唯一标识符
 * @param bool $trim
 * @return string
 */
function guid_v4(bool $trim = true): string
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
 * 生成密码hash值
 * @param string $password
 * @return string
 */
function encryption_hash(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}


/**
 * 设置默认的检索数据
 * @param array $query
 * @param array $default
 * @return array
 */
function set_query_default_value(array $query, array $default = []): array
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
function get_array_column($source, $column): array
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
function linear_to_tree(array $data, string $sub_key_name = 'sub', string $id_name = 'id', string $parent_id_name = 'pid', int $parent_id = 0): array
{
    $tree = [];
    if (empty($data)) return $tree;
    foreach ($data as $row) {
        if ($row[$parent_id_name] == $parent_id) {
            $temp = $row;
            $child = linear_to_tree($data, $sub_key_name, $id_name, $parent_id_name, $row[$id_name]);
            if ($child) {
                $temp[$sub_key_name] = $child;
            }
            $tree[] = $temp;
        }
    }
    return $tree;
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
 * 从object中选取属性
 * @param $source
 * @param array $columns
 * @return array
 */
function get_object_pick($source, array $columns): array
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
function get_array_rand(array $source, int $num): array
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
function array_column2_Key($source, $index): array
{
    $data = [];
    if (empty($source)) return $data;
    foreach ($source as $item) {
        $data[$item[$index]] = $item;
    }
    return $data;
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
    !is_dir($path) && mkdir($path, 0755, true);
}