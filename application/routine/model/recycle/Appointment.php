<?php
/**
 *
 * @author: zkk
 * @day: 2019/10/18
 */

namespace app\routine\model\recycle;

use basic\ModelBasic;
use traits\ModelTrait;

class Appointment extends ModelBasic
{
    use ModelTrait;

    // 回收预约
    public static function getRecycleOrder($field = '*',$uid)
    {
        $model = self::where('uid',$uid)->field($field)->select();
        return $model;
    }

}





