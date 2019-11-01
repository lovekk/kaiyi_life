<?php
/**
 *
 * @author: zkk
 * @day: 2019/10/18
 */

namespace app\routine\model\user;

use basic\ModelBasic;
use traits\ModelTrait;

class School extends ModelBasic
{
    use ModelTrait;

    // 学校接口
    public static function getSchool($field = '*')
    {
        // 类型1学校2小区
        // 是否入住1入住0未入住
        $model = self::where('type',1)
            ->where('is_show',1)
            ->order('sort','desc')
            ->field($field)
            ->select();
        return $model;
    }
    // 小区接口
    public static function getHome($field = '*')
    {
        // 类型1学校2小区
        $model = self::where('type',2)
            ->where('is_show',1)
            ->order('sort','desc')
            ->field($field)
            ->select();
        return $model;
    }

}





