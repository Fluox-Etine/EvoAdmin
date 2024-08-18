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

            $offset = max(0, ((int)$params['page'] ?? 0 - 1) * 15);
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
            exceptionLog($e);
            return [];
        }
    }


    /**
     * 获取表详情
     * @param string $tableName
     * @return array
     */
    public static function tableSheetDetail(string $tableName): array
    {
        try {
            $database = config('database.connections.mysql.database');
            $sql = "SELECT COLUMN_NAME,COLUMN_DEFAULT,IS_NULLABLE,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH,NUMERIC_PRECISION,NUMERIC_SCALE,DATA_TYPE AS COLUMN_TYPE,COLUMN_KEY,EXTRA,COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '{$database}' AND TABLE_NAME = '{$tableName}'";
            return Db::select($sql);
        } catch (\Exception $e) {
            exceptionLog($e);
            return [];
        }
    }
}