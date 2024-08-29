<?php
declare (strict_types=1);

namespace app\common\model\upload;

use app\common\model\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 文件分组模型类
 * Class GroupModel
 * @package app\common\model\upload
 * @date 2024/08/29 10:41
 */
class GroupModel extends BaseModel
{
    use SoftDeletes;

    /** 与模型关联的表名 * @var string */
    protected $table = 'upload_group';

    /** 重定义主键，默认是id @var string */
    protected $primaryKey = 'id';
}