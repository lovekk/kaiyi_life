<?php
/**
 *
 * @author: zkk
 * @day: 2019/10/18
 */

namespace app\routine\model\recycle;

use basic\ModelBasic;
use traits\ModelTrait;

class RecycleAppointment extends ModelBasic
{
    use ModelTrait;

    // 价格接口
    public static function getRecycleOrder($field = '*',$uid)
    {
        $model = self::where('uid',$uid)->field($field)->select();
        return $model;
    }

}





