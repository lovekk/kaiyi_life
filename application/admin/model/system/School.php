<?php
/**
 *
 * @author: zk
 * @day: 2019/10/11
 */

namespace app\admin\model\system;

use traits\ModelTrait;
use basic\ModelBasic;

/**
 * Class SystemAdmin
 * @package app\admin\model\system
 */
class School extends ModelBasic
{
    use ModelTrait;
    public static function systemPage($params)
    {
        $model = new self;
        if($params['keyword'] !== '') $model = $model->where('name|type','LIKE',"%$params[keyword]%");
        $model = $model->order('sort DESC,id DESC');
        return self::page($model,$params);
    }

    public static function getSchool(){
        $model = self::where('is_show',1)
            ->order('sort','desc')
            ->select();
        return $model;
    }
}