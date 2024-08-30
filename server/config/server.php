<?php

return [
    'listen' => 'http://0.0.0.0:19878',
    'transport' => 'tcp',
    'context' => [],
    'name' => 'EvoAdmin',
    'count' => cpu_count(),
    'user' => '',
    'group' => '',
    'reusePort' => false,
    'event_loop' => '',
    'stop_timeout' => 2,
    'pid_file' => runtime_path() . '/webman.pid',
    'status_file' => runtime_path() . '/webman.status',
    'stdout_file' => runtime_path() . '/logs/stdout.log',
    'log_file' => runtime_path() . '/logs/workerman.log',
    'max_package_size' => 10 * 1024 * 1024
];
