<?php
use Illuminate\Support\Env;

return [
    'default' => [
        'host' => Env::get('REDIS_HOST', '127.0.0.1'),
        'password' => Env::get('REDIS_PASSWORD', null),
        'port' => Env::get('REDIS_PORT', 6379),
        'database' => Env::get('REDIS_DATABASE', 1),
    ],
];
