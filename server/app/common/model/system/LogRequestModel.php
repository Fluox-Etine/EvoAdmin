<?php
declare (strict_types=1);

namespace app\common\model\system;

use app\common\model\BaseModel;


/**
 * 请求日志模型类
 * Class LogRequestModel
 * @package app\common\model\system
 * @date 2024/09/10 20:21
 */
class LogRequestModel extends BaseModel
{
    /** 与模型关联的表名 * @var string */
    protected $table = 'sys_log_request';

    /** 重定义主键，默认是id @var string */
    protected $primaryKey = 'id';
}