<?php

namespace app\common\enum;

enum RedisKeyEnum: string
{
    // 队列日志中间件
    case REDIS_QUEUE_LOG_MIDDLEWARE = 'queue-log-middleware';
    // 慢SQL分析列表
    case REDIS_SLOW_SQL_LIST = 'slow-sql-list';

    // 管理员token
    case ADMIN_TOKEN = 'ADMIN_TOKEN:';
}
