<?php

namespace app\http\admin\service\system;

use app\common\enum\RedisKeyEnum;
use app\common\model\sys\SysAdminModel;
use support\exception\RespBusinessException;
use support\Redis;

class SysAdminService
{

    /**
     * 登录
     * @param array $param
     * @return string
     * @throws RespBusinessException
     */
    public static function handleLogin(array $param): string
    {
        try {
            $detail = SysAdminModel::query()->where('username', $param['username'])->first();
            if (is_null($detail)) {
                throw new \Exception('用户不存在');
            }
            if (!password_verify($param['password'], $detail->password)) {
                throw new \Exception('密码错误');
            }
            return self::makeToken($detail->id);
        } catch (\Exception $e) {
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
        return $id;
    }
}