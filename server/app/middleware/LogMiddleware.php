<?php

namespace app\middleware;

use support\Context;
use support\Db;
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
        $data = [
            'ip' => get_ip(),
            'uri' => $request->uri(),
            'method' => $request->method(),
            'uuid' => Context::get('Request-traceId'),
            'user_agent' => get_user_agent(),
            'query' => is_array($request->all()) ? jsonEncode($request->all()) : $request->all(),
            'created_at' => time(),
        ];

        $response = $handler($request);
        $err = $response->exception();
        // 判断当前接口是否异常报错了
        if (isset($err)) {
            $data['status'] = 20;
            $data['response'] = $err->getMessage();
        } else {
            $data['status'] = 10;
            $data['response'] = is_array($response->rawBody()) ? jsonEncode($response->rawBody()) : $response->rawBody();
        }

        $end = microtime(true);
        $exec_time = round(($end - Context::get('Request-start')) * 1000, 2);
        $data['exec_time'] = $exec_time;
        $data['uid'] = Context::get('Request-aid') ?? 0;
        $data['pid'] = getmypid();
        if (in_array($data['uri'], config('env.log.query_exclude'))) {
            $data['query'] = '';
        }
        if (in_array($data['uri'], config('env.log.response_exclude'))) {
            $data['response'] = '';
        }
        Db::table('sys_log_request')->insert($data);
        return $response;
    }

}