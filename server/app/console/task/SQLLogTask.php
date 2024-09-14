<?php

namespace app\console\task;

use app\common\enum\RedisKeyEnum;
use Illuminate\Support\Str;
use support\Db;
use support\Log;
use support\Redis;
use Workerman\Timer;

/**
 * SQL日志分析器
 * Class SQLLogTask
 * @package app\console\task
 */
class SQLLogTask
{
    /**
     * 启动定时任务
     * @return void
     */
    public function onWorkerStart()
    {
        Timer::add(config('env.log.sql_log_timer', 180), function () {
            var_dump('SQL日志落入数据库定时任务 === 执行时间' . date('Y-m-d H:i:s'));
            if (Redis::llen(RedisKeyEnum::MYSQL_LOG->value)) {
                self::handleSQLLog();
            } else {
                // 处理慢日志
                if (Redis::llen(RedisKeyEnum::SLOW_SQL_LIST->value)) {
                    self::handleSlowSQL();
                }
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


    /**
     * 处理慢SQL日志
     * @return void
     */
    private static function handleSlowSQL()
    {
        try {
            $list = [];
            Redis::pipeline(function ($pipe) use (&$list) {
                for ($i = 0; $i < 1; $i++) {
                    $pipe->lpop(RedisKeyEnum::SLOW_SQL_LIST->value);
                }
                $list = $pipe->exec();
            });
            if (!empty($list)) {
                $data = [];
                foreach ($list as $item) {
                    if (!empty($item)) {
                        $tmp = jsonDecode($item);
                        $sqlType = substr($tmp['sql'], 0, 6);

                        if ($sqlType == 'select') {
                            // 执行explain  // "select * from `evo_sys_admin` where `username` = ? and `evo_sys_admin`.`deleted_at` is null limit 1
                            $sql = $tmp['sql'];
                            $bindings = $tmp['bindings'];
                            if ($bindings) {
                                foreach ($bindings as $v) {
                                    if (is_numeric($v)) {
                                        $bindings[] = $v;
                                    } else {
                                        $bindings[] = '"' . strval($v) . '"';
                                    }
                                }
                            }
                            $execute = Str::replaceArray('?', $bindings, $sql);
                            $explain = Db::select("explain $execute");
                            $data[] = [
                                'sql' => $tmp['sql'],
                                'bindings' => jsonEncode($tmp['bindings']),
                                'exec_time' => $tmp['time'],
                                'created_at' => time(),
                                'explain' => jsonEncode($explain[0]),
                            ];
                        } else {
                            $data[] = [
                                'sql' => $tmp['sql'],
                                'bindings' => jsonEncode($tmp['bindings']),
                                'exec_time' => $tmp['time'],
                                'created_at' => time(),
                            ];
                        }
                    }
                    Db::table('sys_slow_sql')->insert($data);
                }
            }
        } catch (\Throwable $e) {
            Log::error('慢SQL日志落库失败' . format_log($e));
        }
    }
}