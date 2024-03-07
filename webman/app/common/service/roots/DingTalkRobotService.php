<?php

namespace app\common\service\roots;

class DingTalkRobotService
{
    /**
     * 发送钉钉机器人
     * @param array $args
     * @param string $name
     * @param string $text
     * @return bool|string
     */
    public static function DingTalkRobot(array $args, string $name = '', string $text = '')
    {
        $config =  config('server.dingtalk');
        $accessToken = $config['access_token'];
        $secret = $config['secret'];
        $title = $config['title'];
        $message  = ' - <font color="#dd00dd">监控来源： ' .$title. "</font> \n";
        if (!empty($name)) {
            $title = $name;
            $message = ' - <font color="#dd0000">监控来源： ' .$name. "</font> \n";
        }

        if (!empty($text)) {
            $message .= $text;
        }
        $level = "";
        switch ($args['level']) {
            case '信息等级':
                $level = '<font color="#3498db">' . $args['level'] . '</font>';
                break;
            case '警告等级':
                $level = '<font color="#f39c12" >' . $args['level'] . '</font>';
                break;
            case '错误等级':
                $level = '<font color="#FF0000">' . $args['level'] . '</font>';
                break;
            case '崩溃等级':
                $level = '<font color="#AE0000">' . $args['level'] . '</font>';
                break;
            default:
                // 默认情况下不改变颜色
                $level = $args['level'];
                break;
        }
        $message .= ' - 响应错误： ' .$args['message']. " \n";
        $message .= ' - 警告级别：' . $level . " \n";
        $message .= ' - 请求域名：' . $args['domain'] . " \n";
        $message .= ' - 请求路由：' . $args['request_url'] . " \n";
        $message .= ' - 请求IP：' . $args['client_ip'] . " \n";
        $message .= ' - 请求时间：' . $args['timestamp'] . " \n";
        $message .= ' - 请求参数：' . json_encode($args['request_param'], JSON_UNESCAPED_UNICODE) . " \n";
        $message .= ' - 异常文件：' . $args['file'] . " \n";
        $message .= ' - 文件行数：' . $args['line'] . " \n";
        $data = [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $title,
                'text' => $message
            ],
            'at' => [
                'isAtAll' => false
            ]
        ];
        $orderPayUrl = 'https://oapi.dingtalk.com/robot/send?access_token=' . $accessToken;
        return  self::request_by_curl(self::_sign($orderPayUrl, $secret), json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    /**
     * @desc: 请求签名
     * @param string $url
     * @param string $secret
     * @return string
     */
    private static function _sign(string $url, string $secret): string
    {
        [$s1, $s2] = explode(' ', microtime());
        $timestamp = (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
        $data = $timestamp . "\n" . $secret;
        $signStr = base64_encode(hash_hmac('sha256', $data, $secret, true));
        if(PHP_VERSION_ID < 82000){
            $signStr = mb_convert_encoding($signStr, 'UTF-8', 'ISO-8859-1');
        } else {
            $signStr = utf8_encode(urlencode($signStr));
        }
        $signStr = mb_convert_encoding(urlencode($signStr), 'UTF-8', 'ISO-8859-1');
        return $url . "&timestamp=$timestamp&sign=$signStr";
    }

    /**
     * @desc: 自定义请求类
     * @param string $remote_server
     * @param string $postString
     * @return bool|string
     */
    private static function request_by_curl(string $remote_server, string $postString)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}