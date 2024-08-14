<?php

namespace app\http\generate\service;

use support\Db;

class TableService
{

    /**
     * 获取所有数据表
     * @param array $params
     * @return array
     */
    public static function tableSheet(array $params): array
    {
        try {
            $param = setQueryDefaultValue($params, [
                'name' => null,
                'comment' => null
            ]);
            $sql = 'SHOW TABLE STATUS WHERE 1=1 ';
            !empty($param['name']) && $sql .= " AND Name LIKE '%" . $params['name'] . "%'";
            !empty($param['comment']) && $sql .= " AND Comment LIKE '%" . $params['comment'] . "%'";
            $list = Db::select($sql);
            $excludeTable = config('env.generate.exclude_table');
            $data = [];
            foreach ($list as $value) {
                if (in_array($value->Name, $excludeTable)) {
                    continue;
                }
                $data[] = [
                    'name' => $value->Name,
                    'comment' => $value->Comment,
                    'create_time' => $value->Create_time,
                    'update_time' => $value->Update_time,
                ];
            }

            $offset = max(0, ((int)$params['page'] - 1) * 15);
            $count = count($data);
            $data = array_slice($data, $offset, 15, true);

            return [
                'total' => $count,
                'current_page' => (int)$params['page'],
                'per_page' => 15,
                'last_page' => ceil($count / 15),
                'data' => $data,
            ];
        } catch (\Throwable $e) {
            return [];
        }
    }
}