<?php

namespace app\admin\model\recycle;

use function GuzzleHttp\describe_type;
use traits\ModelTrait;
use basic\ModelBasic;

/**
 * Class SystemAdmin
 * @package app\admin\model\system
 */
class Price extends ModelBasic
{
    use ModelTrait;
    public static function systemPage($params)
    {
        $model = new self;
        if($params['keyword'] !== '') $model = $model->where('name|unit','LIKE',"%$params[keyword]%");
        $model = $model->order('sort DESC,id DESC');
        return self::page($model,$params);
    }

}