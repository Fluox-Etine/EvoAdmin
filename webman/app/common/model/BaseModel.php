<?php
declare (strict_types=1);

namespace app\common\model;

use DateTimeInterface;
use support\Model;

class BaseModel extends Model
{
    // 时间戳格式化时间问题
    public function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
    // 隐藏字段
    protected $hidden = ['deleted_at'];

    // 设置默认生成时间为时间戳
    protected $dateFormat = 'U';

}