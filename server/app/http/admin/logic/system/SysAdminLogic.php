<?php

namespace app\http\admin\logic\system;

use app\common\model\sys\SysAdminModel;
use app\http\admin\service\system\SysAdminService;
use support\exception\RespBusinessException;

class SysAdminLogic
{
    /**
     * 获取登录信息
     * @return array
     * @throws RespBusinessException
     */
    public static function handleProfile(): array
    {
        $detail = SysAdminModel::query()->where('id', SysAdminService::getCurrentLoginId())->first();
        if (is_null($detail)) {
            throw new RespBusinessException('用户信息不存在');
        }
        unset($detail->password);
        return $detail->toArray();
    }
}