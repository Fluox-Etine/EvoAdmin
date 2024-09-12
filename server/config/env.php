<?php

return [
    'http' => [
        'success_code' => 200,
        'error_code' => 500
    ],
    'generate' => [
        'exclude_table' => [], // 排除部分数据表禁止生成'
        'template_dir' => base_path() . '/app/generate/service/stub/', // 模板路径
        'generator_dir' => run_path() . '/generate/', // 生成路径
    ],

    // 慢SQL拦截
    'show_sql' => [
        'enable' => true,  // 是否开启
        'limit' => 1000,    // sql执行时间大于多少秒进行拦截（单位毫秒 默认1000）
    ],

    // 上传文件配置
    'upload' => [
        // 允许上传文件类型
        'allow_ext' => [
            8297 => 'rar',
            255216 => 'jpg',
            7173 => 'gif',
            6677 => 'bmp',
            13780 => 'png',
            8273 => 'webp'
        ],
        // 文件上传大小限制
        'max_size' => 1024 * 1024 * 1024,
        // 文件上传目录
        'upload_dir' => '/uploads/' . date('Ymd') . '/',
        // 文件访问域名
        'domain' => 'http://localhost:19878',
        // 切片文件上传临时目录
        'chunk_dir' => '/uploads/chunk/',
    ],

    // 日志功能配置
    'log' => [
        // 禁止记录的uri
        'exclude_uri' => [
            '/v1/console/auth/login',
            '/v1/console/system/log/request/detail',
            '/v1/console/system/log/request/list',
            '/v1/console/system/log/login/list'
        ],
        // 禁止请求参数记录
        'query_exclude' => [
            '/v1/console/auth/login'
        ],
        // 禁止响应参数记录
        'response_exclude' => [
            '/v1/console/auth/login'
        ],
        // sql的落库时间定时器间隔
        'sql_log_timer' => 180,
        // 每次落库的sql数量
        'sql_log_limit' => 50,
        // 排除记录数据表的名称（全名称） // 只是排除了 insert update delete
        'exclude_table' => [
            'evo_sys_log_login',
            'evo_sys_log_mysql',
            'evo_sys_log_request'
        ]
    ],

    // 命令行配置
];