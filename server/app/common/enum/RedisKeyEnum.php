<?php

namespace app\common\enum;

enum RedisKeyEnum: string
{
    // mysql的日志中间件
    case MYSQL_LOG = 'mysql-log';
    // 慢SQL分析列表
    case REDIS_SLOW_SQL_LIST = 'slow-sql-list';

    // 管理员token
    case ADMIN_TOKEN = 'ADMIN_TOKEN:';
}
