<?php
return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver'      => 'mysql',
            'host'        => 'localhost',
            'port'        => 3306,
            'database'    => 'evo-php-admin',
            'username'    => 'root',
            'password'    => '123456',
            'unix_socket' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_0900_ai_ci',
            'prefix'      => 'tpl_',
            'strict'      => true,
            'engine'      => null,
            'options' => [
                \PDO::ATTR_TIMEOUT => 3
            ]
        ],
    ],
];