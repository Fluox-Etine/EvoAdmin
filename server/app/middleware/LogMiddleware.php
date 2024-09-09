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
        $traceId = guidV4();
        $data = [
            'ip' => get_ip(),
            'uri' => $request->uri(),
            'method' => $request->method(),
            'traceId' => $traceId,
            'user_agent' => get_user_agent(),
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
        Log::info(RedisKeyEnum::REDIS_QUEUE_LOG_MIDDLEWARE->value, $data);
        // Redis::send(RedisKeyEnum::REDIS_QUEUE_LOG_MIDDLEWARE, $data);
        return $response;
    }

}