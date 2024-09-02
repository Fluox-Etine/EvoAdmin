<?php

namespace app\http\generate\service\console;

use app\http\generate\service\GenerateService;

class TableService
{
    /**
     * 生成表格文件
     * @param array $params
     * @return string
     */
    public static function handleTable(array $params): string
    {
        // 需要替换的变量
        $needReplace = [
            '{HEADER_TITLE}', // 名称
            '{SEARCH}', // 搜索
            '{TOOLBAR}', // 工具栏
            '{FORM_SCHEMAS}', // 表单
            '{API}', // api地址
            '{NAME}', // vue组建名称
            '{PK}', // 数据库主键
            '{FUNCTION}', // 函数
            '{DELETE}', // 是否删除
            '{ACTIONS}', // 操作栏
            '{PAGINATION}'
        ];

        $upperCameNameArray = explode('_', uncamelize($params['upperCameName']));
        $function = '';
        if ($params['gen']['create'] || $params['gen']['update']) {
            $function = PHP_EOL . self::handleFunction($params['PK']) . PHP_EOL;
        }

        $isSearch = false;
        foreach ($params['fields'] as $field) {
            if (isset($field['LIST']) && $field['LIST']) {
                $isSearch = true;
                break;
            }
        }
        $search = !$isSearch ? PHP_EOL . '      :search="false"' : '';
        $formSchemas = '';
        if ($params['gen']['create'] || $params['gen']['update']) {
            $formSchemas = PHP_EOL . "import {formSchemas} from './formSchemas';";
        }
        // 等待替换的内容
        $waitReplace = [
            $params['classComment'],
            $search,
            $params['gen']['create'] ? self::handleToolbar($upperCameNameArray) : '',
            $formSchemas,
            lcfirst($params['upperCameName']),
            $params['upperCameName'],
            $params['PK'],
            $function,
            $params['gen']['deleted'] ? self::handleDelete($params['PK']) : '',
            self::handleAction($upperCameNameArray, $params['gen']['update'], $params['gen']['deleted']),
            $params['paginate'] ? 'true' : 'false',
        ];
        return GenerateService::replaceFileData($needReplace, $waitReplace, GenerateService::getTemplatePath('vue/DynamicTable/index'));
    }

    /**
     * 生成工具栏
     * @param array $upperCameNameArray
     * @return string
     */
    private static function handleToolbar(array $upperCameNameArray): string
    {
        $auth = '';
        foreach ($upperCameNameArray as $value) {
            $auth .= $value . ':';
        }
        // 去除最后一个冒号
        $auth = substr($auth, 0, -1);
        $auth .= ":create";
        $str = '<a-button type="primary" :disabled="!$auth(' . "'" . $auth . "'" . ')" @click="openMenuModal({})">' . PHP_EOL;
        $str .= '           新增' . PHP_EOL;
        $str .= '      </a-button>';
        return $str;

    }

    /**
     * 生成删除方法
     * @param string $pk
     * @return string
     */
    private static function handleDelete(string $pk): string
    {
        return vsprintf(
            "// 删除方法\n" .
            "const delRowConfirm = async (record: TableListItem) => {\n" .
            "  await Api.deleted({%s: record.%s});\n" .
            "  dynamicTableInstance.reload();\n" .
            "};\n",
            [
                $pk,
                $pk
            ]
        );
    }

    /**
     * 生成操作栏
     * @param array $upperCameNameArray
     * @param bool $update
     * @param bool $deleted
     * @return string
     */
    private static function handleAction(array $upperCameNameArray, bool $update, bool $deleted): string
    {
        $auth = '';
        foreach ($upperCameNameArray as $value) {
            $auth .= $value . ':';
        }
        $auth = substr($auth, 0, -1);
        $str = '';
        if ($update) {
            $str .= vsprintf(
                "     {
        label: '编辑',
        auth: {
          perm: '%s',
          effect: 'disable',
        },
        onClick: () => openMenuModal(record),
    },", [$auth . ":update"]);
        }
        if ($deleted) {
            if (!empty($str)) $str .= PHP_EOL;
            $str .= vsprintf(
                "     {
        label: '删除',
        auth: {
          perm: '%s',
          effect: 'disable',
        },
        onClick: () => delRowConfirm(record),
    },", [$auth . ":delete"]);
        }
        if (!empty($str)) {
            $action = "  {
    title: '操作',
    width: 130,
    dataIndex: 'ACTION',
    hideInSearch: true,
    fixed: 'right',
    actions: ({record}) => [" . PHP_EOL;
            $action .= $str.PHP_EOL;
            $action .= "    ]
  },";
            return $action;
        }
        return '';
    }


    /**
     * 生成函数
     * @param string $pk
     * @return string
     */
    private static function handleFunction(string $pk): string
    {
        // 需要替换的变量
        $needReplace = [
            '{PK}', // 数据库主键
        ];
        // 等待替换的内容
        $waitReplace = [$pk];
        return GenerateService::replaceFileData($needReplace, $waitReplace, GenerateService::getTemplatePath('vue/DynamicTable/function'));
    }
}