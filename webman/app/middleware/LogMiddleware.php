<?php

namespace app\middleware;

use app\common\enum\RedisKeyEnum;
use support\Context;
use support\Log;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 日志中间件
 * Class LogMiddleware
 */
class LogMiddleware implements MiddlewareInterface
{
    /**
     * 日志中间件
     * @param Request $request
     * @param callable $handler
     * @return Response
     */
    public function process(Request $request, callable $handler): Response
    {
        $start = microtime(true);
        $traceId = guid_v4();
        $data = [
            'ip' => $this->getIp($request),
            'uri' => $request->uri(),
            'method' => $request->method(),
            'traceId' => $traceId,
            'refer' => $request->header('referer'),
            'user_agent' => $request->header('user-agent'),
            'query' => $request->all(),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        //记录全局traced 后期做异常追踪有用
        Context::set('traceId', $data['traceId']);

        $response = $handler($request);
        $err = $response->exception();
        // 判断当前接口是否异常报错了
        if (isset($err)) {
            $data['error'] = $err->getMessage();
        } else {
            $data['error'] = '';
        }
        $end = microtime(true);
        $exec_time = round(($end - $start) * 1000, 2);
        $data['exec_time'] = $exec_time;
        // 投递到异步日志队列
        Log::info(RedisKeyEnum::REDIS_QUEUE_LOG_MIDDLEWARE, $data);
        // Redis::send(RedisKeyEnum::REDIS_QUEUE_LOG_MIDDLEWARE, $data);
        return $response;
    }

    /**
     * 获取客户端IP
     * @param Request $request
     * @return bool|string
     */
    private function getIp(Request $request): bool|string
    {
        $forward_ip = $request->header('X-Forwarded-For');
        $ip1 = $request->header('x-real-ip');
        $ip2 = $request->header('remote_addr');
        if (!$ip1 && !$ip2 && !$forward_ip) {
            return false;
        }
        $request_ips = [];
        if ($forward_ip) {
            $request_ips[] = $forward_ip;
        }
        if ($ip1) {
            $request_ips[] = $ip1;
        }
        if ($ip2) {
            $request_ips[] = $ip2;
        }
        return implode(',', $request_ips);
    }
}