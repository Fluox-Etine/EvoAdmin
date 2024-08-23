<?php
declare (strict_types=1);

namespace app\common\model;

use app\common\model\sys\SysRoleModel;
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


    /**
     * 检查条件是否存在
     * @param array $where
     * @return bool
     */
    public static function checkExists(array $where): bool
    {
        return BaseModel::query()->where($where)->exists();
    }

}