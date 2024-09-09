<?php

namespace app\http\admin\logic\system;

use app\common\model\sys\LogLoginModel as SystemLoginLogModel;
use support\exception\RespBusinessException;

/**
 * 登录日志逻辑类
 * Class LogLoginLogic
 * @package app\http\admin\logic\sys
 * @date 2024/09/09 21:40
 */
class LogLoginLogic
{


    /**
     * 获取登录日志列表
     * @param array $params
     * @return array
     * @throws RespBusinessException
     * @date 2024/09/09 21:40
     */
    public static function handleLists(array $params): array
    {
        try {
            $filter = [];
            $param = setQueryDefaultValue($params, [
                'username' => null,
                'ip' => null,
                'status' => null,
                'uid' => null
            ]);

            !empty($param['username']) && $filter[] = ['username', 'like', '%' . $param['username'] . '%'];
            !empty($param['ip']) && $filter[] = ['ip', '=', $param['ip']];
            !empty($param['status']) && $filter[] = ['status', '=', $param['status']];
            !empty($param['uid']) && $filter[] = ['uid', '=', $param['uid']];

            $list = SystemLoginLogModel::query()->where($filter)->select('id', 'username', 'ip', 'user_agent', 'updated_at', 'status', 'uid')->orderByDesc('id')->paginate($params["pageSize"] ?? 10);
            return formattedPaginate($list);
        } catch (\Exception $e) {
            throw new RespBusinessException('查询数据异常');
        }
    }

}