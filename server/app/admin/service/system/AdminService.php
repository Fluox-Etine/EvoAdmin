<?php

namespace app\admin\service\system;

use app\common\enum\RedisKeyEnum;
use app\common\model\system\AdminModel;
use app\common\model\system\LogLoginModel as SysLoginLogModel;
use support\Context;
use support\exception\RespBusinessException;
use support\Redis;

class AdminService
{

    /**
     * 登录
     * @param array $param
     * @return string
     * @throws RespBusinessException
     */
    public static function handleLogin(array $param): string
    {
        $log = [];
        try {
            $detail = AdminModel::query()->where('username', $param['username'])->first();
            if (is_null($detail)) {
                $log['status'] = 20;
                throw new \Exception('用户不存在');
            }
            $log['uid'] = $detail->id;
            $log['username'] = $param['username'];
            if (!password_verify($param['password'], $detail->password)) {
                $log['status'] = 30;
                throw new \Exception('密码错误');
            }
            $log['status'] = 10;
            self::handleLoginLog($log);
            return self::makeToken($detail->id);
        } catch (\Exception $e) {
            self::handleLoginLog($log);
            throw new RespBusinessException($e->getMessage());
        }
    }


    /**
     * 生成token
     * @param int $id
     * @return string
     */
    protected static function makeToken(int $id): string
    {
        $guid = guidV4();
        $timeStamp = microtime(true);
        $token = md5($guid . $timeStamp . $id);
        Redis::setEx(RedisKeyEnum::ADMIN_TOKEN->value . $token, 60 * 60 * 24 * 30, $id);
        return $token;
    }


    /**
     * 获取当前登录用户id
     * @return int
     * @throws RespBusinessException
     */
    public static function getCurrentLoginId(): int
    {
        $token = get_token();
        if (empty($token)) {
            throw new RespBusinessException('非法登录');
        }
        $id = Redis::get(RedisKeyEnum::ADMIN_TOKEN->value . $token);
        if (empty($id)) {
            throw new RespBusinessException('身份验证信息已过期');
        }
        Context::set('Request-aid', $id);
        return $id;
    }

    /**
     * 登录日志
     * @param array $log
     */
    private static function handleLoginLog(array $log): void
    {
        $log['ip'] = get_ip();
        $log['user_agent'] = get_user_agent();
        $log['updated_at'] = time();
        $log['pid'] = getmypid();
        SysLoginLogModel::insert($log);
    }
}