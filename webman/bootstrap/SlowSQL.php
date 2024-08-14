<?php

namespace bootstrap;

use app\common\enum\RedisKeyEnum;
use support\Db;
use support\Log;
use support\Redis;
use Webman\Bootstrap;

class SlowSQL implements Bootstrap
{
    public static function start($worker): void
    {
        if (config('env.show_sql.enable')) {
            $slowSqlData = [];
            Db::listen(function ($query) use (&$slowSqlData) {
                // 这里去除框线的心跳ping
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
                }
                Log::info('MySQL: ', ['PID' => getmypid(), 'SQL' => $query->sql, 'Bindings' => $query->bindings, 'Time' => $query->time . 'ms']);
            });
        }
    }
}