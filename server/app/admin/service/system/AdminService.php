<?php

namespace app\admin\service\system;

use app\common\enum\RedisKeyEnum;
use app\common\model\system\AdminModel;
use app\common\model\system\LogLoginModel as SysLoginLogModel;
use support\Cache;
use support\exception\RespBusinessException;

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
            $token = self::makeToken($detail->id);
            unset($detail->password);
            Cache::set(RedisKeyEnum::ADMIN_TOKEN->value . $token, $detail->toArray(), 60 * 60 * 24);
            return $token;
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
        return md5($guid . $timeStamp . $id);
    }


    /**
     * 获取当前登录用户信息
     * @return mixed
     * @throws RespBusinessException
     */
    public static function getCurrentLoginInfo(): mixed
    {
        $token = get_token();
        if (empty($token)) {
            throw new RespBusinessException('非法登录');
        }
        $detail = Cache::get(RedisKeyEnum::ADMIN_TOKEN->value . $token);
        if (empty($detail)) {
            throw new RespBusinessException('身份验证信息已过期');
        }
        return $detail;
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
        $id = Cache::get(RedisKeyEnum::ADMIN_TOKEN->value . $token)['id'];
        if (empty($id)) {
            throw new RespBusinessException('身份验证信息已过期');
        }
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

    /**
     * 获取日志登录操作人id
     * @return int
     */
    public static function handleLogUid(): int
    {
        $token = get_token();
        if (empty($token)) {
            return 0;
        }
        return Cache::get(RedisKeyEnum::ADMIN_TOKEN->value . $token)['id'] ?? 0;
    }
}