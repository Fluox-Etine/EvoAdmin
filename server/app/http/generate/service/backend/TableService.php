<?php

namespace app\http\generate\service\backend;

use app\http\generate\service\GenerateService;
use support\Db;
use support\exception\RespBusinessException;

class TableService
{

    /**
     * 获取所有数据表
     * @return array
     */
    public static function tableSheet(): array
    {
        try {

            $sql = 'SHOW TABLE STATUS';
//            !empty($param['name']) && $sql .= " AND Name LIKE '%" . $params['name'] . "%'";
//            !empty($param['comment']) && $sql .= " AND Comment LIKE '%" . $params['comment'] . "%'";
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
                    'engine' => $value->Engine,
                    'create_time' => $value->Create_time,
                ];
            }
            return [
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
     * @throws RespBusinessException
     */
    public static function tableSheetDetail(string $tableName): array
    {
        try {
            $database = config('database.connections.mysql.database');
            $sqlTable = "SHOW TABLE STATUS WHERE  Name = " . "'{$tableName}'";
            $table = Db::select($sqlTable);
            if (empty($table)) {
                throw new \Exception('数据表不存在');
            }
            $table = $table[0];
            $sqlFields = "SELECT COLUMN_NAME,COLUMN_DEFAULT,IS_NULLABLE,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH,NUMERIC_SCALE,DATA_TYPE,COLUMN_KEY,COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '{$database}' AND TABLE_NAME = '{$tableName}'";
            $fields = Db::select($sqlFields);
            if (!empty($fields)) {
                foreach ($fields as &$field) {
                    if ($field->IS_NULLABLE === 'NO' && $field->COLUMN_DEFAULT === null && $field->COLUMN_KEY !== 'PRI') {
                        $field->IS_NULLABLE = 1;
                        $field->LIST = 1;
                        $field->CREATE = 1;
                        $field->UPDATE = 1;
                        $field->DETAIL = 1;
                        $field->FILTER = 1;
                        $field->VALIDATE = [1];
                    } else {
                        $field->IS_NULLABLE = 0;
                        $field->LIST = 0;
                        $field->CREATE = 0;
                        $field->UPDATE = 0;
                        $field->DETAIL = 0;
                        $field->FILTER = 0;
                        $field->VALIDATE = [];
                    }
                    $field->QUERY_TYPE = '';
                    if ($field->COLUMN_KEY === 'PRI') {
                        $table->PK = $field->COLUMN_NAME;
                    }
                }
            }
            $tableName = GenerateService::getNoPrefixTableName($table->Name);
            $tableNameArray = explode('_', $tableName);
            $upperCameName = GenerateService::underscoreToCamelCase($tableName);
            if (!empty($tableNameArray) && count($tableNameArray) > 1) {
                $table->classDir = $tableNameArray[0];
            } else {
                $table->classDir = '';
            }
            $table->upperCameName = $upperCameName;
            return [
                'fields' => $fields,
                'table' => $table ?? []
            ];
        } catch (\Throwable $e) {
            exceptionLog($e);
            throw new RespBusinessException($e->getMessage());
        }
    }
}