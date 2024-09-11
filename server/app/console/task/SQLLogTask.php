<?php

namespace app\console\task;

use app\common\enum\RedisKeyEnum;
use support\Db;
use support\Log;
use support\Redis;
use Workerman\Timer;

class SQLLogTask
{
    public function onWorkerStart()
    {
        Timer::add(config('env.log.sql_log_timer', 180), function () {
            var_dump('SQL日志落入数据库定时任务 === 执行时间' . date('Y-m-d H:i:s'));
            $exist = Redis::llen(RedisKeyEnum::MYSQL_LOG->value);
            if ($exist) {
                self::handleSQLLog();
            }
        });
    }

    /**
     * 处理SQL日志
     * @return void
     */
    protected static function handleSQLLog(): void
    {
        try {
            $list = [];
            Redis::pipeline(function ($pipe) use (&$list) {
                for ($i = 0; $i < (int)config('env.log.sql_log_limit', 50); $i++) {
                    $pipe->lpop(RedisKeyEnum::MYSQL_LOG->value);
                }
                $list = $pipe->exec();
            });
            if (!empty($list)) {
                $data = [];
                foreach ($list as $item) {
                    if (!empty($item)) {
                        $tmp = jsonDecode($item);
                        $data[] = [
                            'sql' => $tmp['sql'],
                            'bindings' => $tmp['bindings'],
                            'exec_time' => $tmp['exec_time'],
                            'pid' => $tmp['pid'],
                            'uuid' => $tmp['uuid'],
                            'created_at' => $tmp['created_at']
                        ];
                    }
                }
                var_dump("本次 SQL日志落入数据库定时任务，共处理" . count($data));
                Db::table('sys_log_mysql')->insert($data);
            }
        } catch (\Throwable $e) {
            Log::error('SQL日志落库失败' . format_log($e));
        }
    }
}