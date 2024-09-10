<?php

namespace bootstrap;

use app\common\enum\RedisKeyEnum;
use support\Context;
use support\Db;
use support\Redis;
use Webman\Bootstrap;

class SlowSQL implements Bootstrap
{
    public static function start($worker): void
    {
        if (config('env.show_sql.enable')) {
            $slowSqlData = [];
            Db::listen(function ($query) use (&$slowSqlData) {
                // 这里去除的心跳ping
                if (strlen($query->sql) > 10) {
                    // 执行时间
                    if ($query->time > config('env.show_sql.limit')) {
                        $md5 = md5($query->sql);
                        if (!in_array($md5, $slowSqlData)) {
                            $slowSqlData[] = $md5;
                            Redis::lpush(RedisKeyEnum::REDIS_SLOW_SQL_LIST->value, jsonEncode([
                                'sql' => $query->sql,
                                'bindings' => $query->bindings,
                                'time' => $query->time
                            ]));
                        }
                    }
                   // 截取前 33 字符。屏蔽特殊字符
                   if(substr($query->sql, 0, 33) != 'insert into `evo_sys_log_request`'){
                       Redis::lPush(RedisKeyEnum::MYSQL_LOG->value, jsonEncode([
                           'sql' => $query->sql,
                           'bindings' => jsonEncode($query->bindings),
                           'exec_time' => $query->time,
                           'pid' => getmypid(),
                           'uuid' => Context::get('Request-traceId') ?? '00000000-0000-0000-0000-0' . time(),
                           'created_at' => time()
                       ]));
                   }

                }
//                Log::info('MySQL:' . format_log(['Request-traceId' => Context::get('Request-traceId'), 'PID' => getmypid(), 'SQL' => $query->sql, 'Bindings' => $query->bindings, 'Time' => $query->time . 'ms']));
            });
        }
    }
}