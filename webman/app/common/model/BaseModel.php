<?php
declare (strict_types=1);

namespace app\common\model;

use support\Model;

class BaseModel extends Model
{
    // 隐藏字段
    protected $hidden = ['deleted_at'];

    // 设置默认生成时间为时间戳
    protected $dateFormat = 'U';

}