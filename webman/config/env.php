<?php

return [
    'http' => [
        'success_code' => 200,
        'error_code' => 500
    ],
    'generate' => [
        'exclude_table' => [], // 排除部分数据表禁止生成'
        'template_dir' => base_path() . '/app/http/generate/service/stub/', // 模板路径
        'generator_dir' => run_path() . '/generate/', // 生成路径
    ],

    // 慢SQL拦截
    'show_sql' => [
        'enable' => false,  // 是否开启
        'limit' => 10,    // sql执行时间大于多少秒进行拦截（单位毫秒 默认1000）
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
        'domain' => 'http://localhost:8080/uploads/',
    ]
];