<?php
declare (strict_types=1);

namespace app\admin\logic\system;

use app\common\model\system\LogMySQLModel as SystemLogMySQLModel;
use app\common\model\system\LogRequestModel as SystemLogRequestModel;
use support\exception\RespBusinessException;

/**
 * 请求日志逻辑类
 * Class LogRequestLogic
 * @package app\admin\logic\system
 * @date 2024/09/10 20:21
 */
class LogRequestLogic
{


    /**
     * 获取请求日志列表
     * @param array $params
     * @return array
     * @throws RespBusinessException
     * @date 2024/09/10 20:21
     */
    public static function handleLists(array $params): array
    {
        try {
            $filter = [];
            $param = setQueryDefaultValue($params, [
                'created_at' => null,
                'ip' => null,
                'uid' => null,
                'uri' => null,
                'status' => null,
            ]);

            !empty($param['created_at']) && $filter[] = ['created_at', '=', $param['created_at']];
            !empty($param['ip']) && $filter[] = ['ip', '=', $param['ip']];
            !empty($param['uid']) && $filter[] = ['uid', '=', $param['uid']];
            !empty($param['uri']) && $filter[] = ['uri', 'like', '%' . $param['uri'] . '%'];
            !empty($param['status']) && $filter[] = ['status', '=', $param['status']];

            $list = SystemLogRequestModel::query()->where($filter)->select('created_at', 'exec_time', 'ip', 'method', 'pid', 'status', 'uid', 'uri', 'user_agent', 'uuid', 'id')->orderByDesc('created_at')->paginate($params["pageSize"] ?? 10);
            return formattedPaginate($list);
        } catch (\Exception $e) {
            exceptionLog($e);
            throw new RespBusinessException('查询数据异常');
        }
    }

    /**
     * 请求日志详情
     * @param array $params
     * @return array
     * @throws RespBusinessException
     * @date 2024/09/10 20:21
     */
    public static function handleDetail(array $params): array
    {
        try {
            if ($params['type'] == 3) {
                $list = SystemLogMySQLModel::query()->where('uuid', $params['id'])->get();
                return empty($list) ? [] : $list->toArray();
            } else {
                $field = $params['type'] == 1 ? 'query' : 'response';
                $detail = SystemLogRequestModel::query()->where('id', $params['id'])->select($field)->first();
                if (is_null($detail)) {
                    // TODO 抛出异常（直接返回，不经过异常）
                    throw new \Exception('数据不存在');
                }
                if ($field === 'query') {
                    return !empty($detail->query) ? jsonDecode($detail->query) : [];
                } else {
                    return !empty($detail->response) ? jsonDecode($detail->response) : [];
                }
            }
        } catch (\Exception $e) {
            exceptionLog($e);
            throw new RespBusinessException('查询数据详情异常');
        }
    }

}