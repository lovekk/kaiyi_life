<?php

namespace app\admin\controller;


use service\UtilService;

class Common extends AuthController
{
    public function rmPublicResource($url)
    {
        if(strpos($url,'public') !== false){
            $res = UtilService::rmPublicResource($url);
        }else{
            $url = ltrim('public'.$url);
            $res = UtilService::rmPublicResource($url);
        }
        if($res->status)
            return $this->successful('删除成功!');
        else
            return $this->failed($res->msg);
    }
}