<?php

return [
    // 全局中间件
    '' => [
        // 跨域中间件
        app\middleware\CrossMiddleware::class,
    ],
    // admin 中间件
    'admin' => [
        // 日志中间件
        app\middleware\LogMiddleware::class,
    ]
];