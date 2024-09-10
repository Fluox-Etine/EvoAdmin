<?php
declare (strict_types=1);

namespace app\admin\validate\upload;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
use support\exception\RespBusinessException;

/**
 * 文件分组验证器类
 * Class GroupValidate
 * @package app\admin\validate\upload
 * @date 2024/08/29 11:17
 */
class GroupValidate
{


    /**
     * 创建数据验证
     * @param array $params
     * @return void
     * @throws RespBusinessException
     * @date 2024/08/29 11:17
     */
    public static function createValidate(array $params): void
    {
        try {
            v::input($params, [
                'name' => v::NotEmpty()->length(1, 15)->setName('分组名称'),
                'sort' => v::NotEmpty()->Number()->setName('分组排序'),
            ]);
        } catch (ValidationException $e) {
            throw new RespBusinessException($e->getMessage());
        }
    }

    /**
     * 编辑数据验证
     * @param array $params
     * @return void
     * @throws RespBusinessException
     * @date 2024/08/29 11:17
     */
    public static function updateValidate(array $params): void
    {
        try {
            v::input($params, [
                'name' => v::NotEmpty()->length(1, 15)->setName('分组名称'),
                'sort' => v::NotEmpty()->Number()->setName('分组排序'),
            ]);
        } catch (ValidationException $e) {
            throw new RespBusinessException($e->getMessage());
        }
    }

}