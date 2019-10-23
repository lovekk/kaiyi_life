<?php
/**
 *
 * @author: zkk
 * @day: 2019/10/18
 */

namespace app\routine\model\recycle;

use basic\ModelBasic;
use traits\ModelTrait;

class Price extends ModelBasic
{
    use ModelTrait;

    // 价格接口
    public static function getPrice($field = '*')
    {
        $model = self::order('sort','desc')->field($field)->select();
        return $model;
    }

}





