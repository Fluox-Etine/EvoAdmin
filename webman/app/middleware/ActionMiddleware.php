<?php

namespace app\middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 方法拦截中间件
 * Class CrossMiddleware
 */
class ActionMiddleware implements MiddlewareInterface
{
    /**
     *方法拦截中间件
     * @param Request $request
     * @param callable $handler
     * @return Response
     */
    public function process(Request $request, callable $handler): Response
    {

        return renderError('演示站点不支持此操作');
    }
}