<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/30
 */

namespace app\routine\model\user;


use basic\ModelBasic;
use traits\ModelTrait;
use app\routine\model\user\User;

class UserBill extends ModelBasic
{
    use ModelTrait;

    protected $insert = ['add_time'];

    protected function setAddTimeAttr()
    {
        return time();
    }


    // 入库
    public static function income($title,$uid,$category,$type,$number,$link_id = 0,$balance = 0,$mark = '',$status = 1)
    {
        $pm = 1;
        return self::set(compact('title','uid','link_id','category','type','number','balance','mark','status','pm'));
    }

    public static function expend($title,$uid,$category,$type,$number,$link_id = 0,$balance = 0,$mark = '',$status = 1)
    {
        $pm = 0;
        return self::set(compact('title','uid','link_id','category','type','number','balance','mark','status','pm'));
    }


    // 二维码转账 转入
    public static function codeincome($uid, $title, $category, $type, $number, $link_id=0, $balance=0, $mark='', $status=1, $user_id, $user_name)
    {
        //0 = 支出 1 = 获得
        $pm = 1;
        $add_time=time();
        // 创建一个包含变量名和它们的值的数组
        return self::set(compact('title','uid','link_id','category','type','number','balance','mark','status','pm','user_id','user_name'));
    }
    // 二维码转账 转出
    public static function codeout($uid, $title, $category, $type, $number, $link_id=0, $balance=0, $mark='', $status=1, $user_id, $user_name)
    {
        $pm = 0;
        $add_time=time();
        return self::set(compact('title','uid','link_id','category','type','number','balance','mark','status','pm','user_id','user_name'));
    }

    // 做改变 2个改值 2个入库
    public static function changeMoney($uid, $title, $category, $type, $number, $link_id=0, $balance=0, $mark='', $status=1, $user_id, $user_name){

        self::beginTrans();
        $res1 = self::codeout($uid, $title, $category, $type, $number, $link_id=0, $balance=0, $mark='', $status=1, $user_id, $user_name);
        $res2 = self::codeincome($uid, $title, $category, $type, $number, $link_id=0, $balance=0, $mark='', $status=1, $user_id, $user_name);
        $res3 = User::changeUserMoney($user_id,$number,1);//转入
        $res4 = User::changeUserMoney($uid,$number,2);//转出
        $res = $res1 && $res2 && $res3 && $res4;
        self::checkTrans($res);
        return $res;
    }




}