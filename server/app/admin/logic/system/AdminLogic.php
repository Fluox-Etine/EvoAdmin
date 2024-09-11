<?php

namespace app\admin\logic\system;

use app\admin\service\system\AdminService;
use app\common\model\system\AdminModel;
use support\exception\RespBusinessException;

class AdminLogic
{
    /**
     * 获取登录信息
     * @return array
     * @throws RespBusinessException
     */
    public static function handleProfile(): array
    {
        $detail = AdminModel::query()->where('id', AdminService::getCurrentLoginId())->first();
        if (is_null($detail)) {
            throw new RespBusinessException('用户信息不存在');
        }
        unset($detail->password);
        return $detail->toArray();
    }
}