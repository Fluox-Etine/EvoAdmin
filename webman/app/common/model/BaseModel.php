<?php
declare (strict_types=1);

namespace app\common\model;

use DateTimeInterface;
use support\Model;

class BaseModel extends Model
{
    // 隐藏字段
    protected $hidden = ['deleted_at'];

    public function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

}