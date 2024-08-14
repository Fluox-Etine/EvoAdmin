<?php

return [
    'generate' => [
        'exclude_table' => ['v4_admin_user'], // 排除部分数据表禁止生成'
        'template_dir' => base_path() . '/app/http/generate/service/stub/', // 模板路径
        'generator_dir' => run_path() . '/generate/', // 生成路径
    ],
];