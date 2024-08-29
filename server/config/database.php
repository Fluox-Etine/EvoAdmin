<?php
use Illuminate\Support\Env;

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver'      => Env::get('DATABASE_DRIVER', 'mysql'),
            'host'        => Env::get('DATABASE_HOST', '127.0.0.1'),
            'port'        => Env::get('DATABASE_PORT', 3306),
            'database'    => Env::get('DATABASE_DATABASE', 'evo_php_admin'),
            'username'    => Env::get('DATABASE_USERNAME', 'root'),
            'password'    => Env::get('DATABASE_PASSWORD', '123456'),
            'unix_socket' => '',
            'charset' => Env::get('DATABASE_CHARSET', 'utf8mb4'),
            'collation' => Env::get('DATABASE_COLLATION', 'utf8mb4_0900_ai_ci'),
            'prefix'      => Env::get('DATABASE_PREFIX', 'evo_'),
            'strict'      => true,
            'engine'      => null,
            'options' => [
                \PDO::ATTR_TIMEOUT => 3
            ]
        ],
    ],
];