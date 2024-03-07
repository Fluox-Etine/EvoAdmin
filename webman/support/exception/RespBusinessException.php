<?php

namespace support\exception;

use Webman\Http\Request;
use Webman\Http\Response;

class RespBusinessException extends BusinessException
{
    // 定义一个机器人错误等级数组
    private  $robotLevel = [
        555 => '信息等级',
        556 => '警告等级',
        557 => '错误等级',
        558 => '崩溃等级'
    ];
    public function render(Request $request): ?Response
    {
        // TODO 重要消息启用🤖️推送。如果 code 为 555则开始推送机器人消息，
        if($this->getCode() >= 555 && $this->getCode() <= 558 && config('server.dingtalk.enable')) {
            // 触发钉钉🤖️
            $service = new \app\common\service\roots\DingTalkRobotService();
            $data = [
                'message' => $this->getMessage(),
                'level' => $this->robotLevel[$this->getCode()] ?? '警告等级',
                'domain' => $request->header('x-forwarded-proto'),
                'request_url' => $request->path(),
                'client_ip' =>  $request->getRealIp($safe_mode=true),
                'timestamp' => date('Y-m-d H:i:s',time()),
                'request_param' => $request->all(),
                'file' => $this->getFile(),
                'line' => $this->getLine(),
            ];
           $service->DingTalkRobot($data);
        }
        return json(['status' => $this->getCode() ?: 500, 'message' => $this->getMessage()]);
    }





}