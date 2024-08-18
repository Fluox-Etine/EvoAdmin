<?php

return [
    'generate' => [
        'exclude_table' => [], // 排除部分数据表禁止生成'
        'template_dir' => base_path() . '/app/http/generate/service/stub/', // 模板路径
        'generator_dir' => run_path() . '/generate/', // 生成路径
    ],

    'show_sql' => [
        'enable' => false,  // 是否开启
        'limit' => 10,    // sql执行时间大于多少秒进行拦截（单位毫秒 默认1000）
    ]
];