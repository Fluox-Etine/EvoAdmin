<?php

namespace app\common\enum;

enum RedisKeyEnum: string
{
    // mysql的日志中间件
    case MYSQL_LOG = 'mysql-log';
    // 慢SQL分析列表
    case SLOW_SQL_LIST = 'slow-sql-list';

    // 管理员token
    case ADMIN_TOKEN = 'ADMIN_TOKEN:';

    // 角色菜单idl
    case ROLE_MENU_IDS = 'ROLE_MENU_IDS:';

    // 所有菜单id
    case ALL_MENU_IDS = 'ALL_MENU_IDS:';

    // 菜单权限id
    case MENU_IDS_PERMISSION = 'MENU_IDS_PERMISSION:';
}
