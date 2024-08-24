<?php
declare (strict_types=1);

namespace app\common\model;

use support\Model;

class BaseModel extends Model
{
    // 隐藏字段
    protected $hidden = ['deleted_at'];

    /**
     * 时间戳格式化
     */
//    public function serializeDate(DateTimeInterface $date): string
//    {
//        return $date->format('Y-m-d H:i:s');
//    }

    public $timestamps = FALSE;


    /**
     * 检查条件是否存在
     * @param BaseModel $model
     * @param array $where
     * @return bool
     */
    public static function checkExists(BaseModel $model, array $where): bool
    {
        return $model::query()->where($where)->exists();
    }

}