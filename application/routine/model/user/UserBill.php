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


    // 二维码转账 转入 凯易币
    public static function codeincome($user_id, $link_id, $title, $category, $type, $number, $balance, $mark, $status, $uid)
    {
        //0 = 支出 1 = 获得
        $pm = 1;
        $add_time=time();
        $balance = $number + User::getUserMoney($uid);
        // 创建一个包含变量名和它们的值的数组
        return self::set(compact('uid','link_id','title','category','type','number','balance','mark','status','pm','add_time'));
    }
    // 二维码转账 转出 凯易币
    public static function codeout($uid, $link_id, $title, $category, $type, $number,  $balance, $mark, $status, $user_id)
    {
        $pm = 0;
        $add_time=time();
        return self::set(compact('uid','link_id','title','category','type','number','balance','mark','status','pm','add_time'));
    }


    // 二维码转账 转入 积分
    public static function codeincomeJ($user_id, $link_id, $title, $category, $type, $number, $balance, $mark, $status, $uid)
    {
        //0 = 支出 1 = 获得
        $pm = 1;
        $add_time=time();
        $balance = $number + User::getUserIntegral($uid);
        // 创建一个包含变量名和它们的值的数组
        return self::set(compact('uid','link_id','title','category','type','number','balance','mark','status','pm','add_time'));
    }


    // 二维码转账 转出 积分
    public static function codeoutJ($uid, $link_id, $title, $category, $type, $number,  $balance, $mark, $status, $user_id)
    {
        $pm = 0;
        $add_time=time();
        return self::set(compact('uid','link_id','title','category','type','number','balance','mark','status','pm','add_time'));
    }


    // 凯易币
    // 做改变 2个改值 2个入库
    public static function changeMoney($uid, $link_id,$title, $category, $type, $number, $balance, $mark, $status,$to_uid){

        self::beginTrans();
        $res1 = self::codeout($uid, $link_id, $title, $category, $type, $number,  $balance, $mark, $status,$to_uid);

        $res2 = self::codeincome($uid, $link_id,$title, $category, $type, $number,  $balance, $mark, $status,$to_uid);

        $res3 = User::changeUserMoney($to_uid,$number,1);//转入

        $res4 = User::changeUserMoney($uid,$number,2);//转出
        $res = $res1 && $res2 && $res3 && $res4;
        self::checkTrans($res);
        return $res;
    }


    // 积分
    // 做改变 2个改值 2个入库
    public static function changeIntegral($uid, $link_id,$title, $category, $type, $number, $balance, $mark, $status,$to_uid){

        self::beginTrans();
        $res1 = self::codeoutJ($uid, $link_id, $title, $category, $type, $number,  $balance, $mark, $status,$to_uid);

        $res2 = self::codeincomeJ($uid, $link_id,$title, $category, $type, $number,  $balance, $mark, $status,$to_uid);

        $res3 = User::changeUserIntegral($to_uid,$number,1);//转入

        $res4 = User::changeUserIntegral($uid,$number,2);//转出

        $res = $res1 && $res2 && $res3 && $res4;

        self::checkTrans($res);

        return $res;
    }





}