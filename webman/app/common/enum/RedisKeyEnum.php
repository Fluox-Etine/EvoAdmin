<?php

namespace app\common\enum;

enum RedisKeyEnum: string
{
    // 队列日志中间件
    case REDIS_QUEUE_LOG_MIDDLEWARE = 'queue-log-middleware';
}
