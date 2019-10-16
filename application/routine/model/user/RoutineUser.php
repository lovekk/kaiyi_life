<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/21
 */

namespace app\routine\model\user;

use app\routine\model\routine\RoutineQrcode;
use basic\ModelBasic;
use traits\ModelTrait;
use app\routine\model\user\User;
use app\routine\model\user\WechatUser;
class RoutineUser extends ModelBasic
{
    use ModelTrait;

    /**
     * 小程序创建用户后返回uid
     * @param $routineInfo
     * @return mixed
     */
    public static function routineOauth($routine){
        $routineInfo['nickname'] = filterEmoji($routine['nickName']);//姓名
        $routineInfo['sex'] = $routine['gender'];//性别
        $routineInfo['language'] = $routine['language'];//语言
        $routineInfo['city'] = $routine['city'];//城市
        $routineInfo['province'] = $routine['province'];//省份
        $routineInfo['country'] = $routine['country'];//国家
        $routineInfo['headimgurl'] = $routine['avatarUrl'];//头像
//        $routineInfo[''] = $routine['code'];//临时登录凭证  是获取用户openid和session_key(会话密匙)
        $routineInfo['routine_openid'] = $routine['routine_openid'];//openid
        $routineInfo['session_key'] = $routine['session_key'];//会话密匙
        $routineInfo['unionid'] = $routine['unionid'];//用户在开放平台的唯一标识符
        $routineInfo['user_type'] = 'routine';//用户类型
        $page = '';//跳转小程序的页面
        $spid = 0;//绑定关系uid
        //获取是否有扫码进小程序
        if($routine['spid']){
            $info = RoutineQrcode::getRoutineQrcodeFindType($routine['spid']);
            if($info){
                $spid = $info['third_id'];
                $page = $info['page'];
            }
        }
        //  判断unionid  存在根据unionid判断
        if($routineInfo['unionid'] != '' && WechatUser::be(['unionid'=>$routineInfo['unionid']])){
            WechatUser::edit($routineInfo,$routineInfo['unionid'],'unionid');
            $uid = WechatUser::where('unionid',$routineInfo['unionid'])->value('uid');
            User::updateWechatUser($routineInfo,$uid);
        }else if(WechatUser::be(['routine_openid'=>$routineInfo['routine_openid']])){ //根据小程序openid判断
            WechatUser::edit($routineInfo,$routineInfo['routine_openid'],'routine_openid');
            $uid = WechatUser::where('routine_openid',$routineInfo['routine_openid'])->value('uid');
            User::updateWechatUser($routineInfo,$uid);
        }else{
            $routineInfo['add_time'] = time();//用户添加时间
            $routineInfo = WechatUser::set($routineInfo);
            if(User::isUserSpread($spid)) {
                $res = User::setRoutineUser($routineInfo,$spid); //用户上级
            }else $res = User::setRoutineUser($routineInfo);
            $uid = $res->uid;
        }
        $data['page'] = $page;
        $data['uid'] = $uid;
        return $data;
    }

    /** 新授权函数
     * @param $routine
     * @return mixed
     */
    public static function routineOauthnew($routine){
        $routineInfo['nickname'] = filterEmoji($routine['nickName']);//姓名
        $routineInfo['sex'] = $routine['gender'];//性别
        $routineInfo['language'] = $routine['language'];//语言
        $routineInfo['city'] = $routine['city'];//城市
        $routineInfo['province'] = $routine['province'];//省份
        $routineInfo['country'] = $routine['country'];//国家
        $routineInfo['headimgurl'] = $routine['avatarUrl'];//头像
//        $routineInfo[''] = $routine['code'];//临时登录凭证  是获取用户openid和session_key(会话密匙)
        $routineInfo['routine_openid'] = $routine['openId'];//openid
        $routineInfo['spreadid'] = $routine['spreadid'];//spreadid
        $routineInfo['session_key'] = $routine['session_key'];//会话密匙
        $routineInfo['unionid'] = isset($routine['unionId'])?$routine['unionId']:'';//用户在开放平台的唯一标识符
        $routineInfo['user_type'] = 'routine';//用户类型
        $page = '';//跳转小程序的页面
        $spid = 0;//绑定关系uid
        //获取是否有扫码进小程序
        if($routine['spid']){
            $info = RoutineQrcode::getRoutineQrcodeFindType($routine['spid']);
            if($info){
                $spid = $info['third_id'];
                $page = $info['page'];
            }
        }
        //  判断unionid  存在根据unionid判断
        if($routineInfo['unionid'] != '' && WechatUser::be(['unionid'=>$routineInfo['unionid']])){
            WechatUser::edit($routineInfo,$routineInfo['unionid'],'unionid');
            $uid = WechatUser::where('unionid',$routineInfo['unionid'])->value('uid');
            User::updateWechatUser($routineInfo,$uid);
        }else if(WechatUser::be(['routine_openid'=>$routineInfo['routine_openid']])){ //根据小程序openid判断
            WechatUser::edit($routineInfo,$routineInfo['routine_openid'],'routine_openid');
            $uid = WechatUser::where('routine_openid',$routineInfo['routine_openid'])->value('uid');
            User::updateWechatUser($routineInfo,$uid);
        }else{
            if($routineInfo['spreadid']) $spid = $routineInfo['spreadid'];
            $routineInfo['add_time'] = time();//用户添加时间
            $routineInfo = WechatUser::set($routineInfo);
            if(User::isUserSpread($spid)) {
                $res = User::setRoutineUser($routineInfo,$spid); //用户上级
            }else $res = User::setRoutineUser($routineInfo);
            $uid = $res->uid;
        }
        $data['page'] = $page;
        $data['uid'] = $uid;
        return $data;
    }
    /**
     * 判断是否是小程序用户
     * @param int $uid
     * @return bool|int|string
     */
    public static function isRoutineUser($uid = 0){
        if(!$uid) return false;
        return WechatUser::where('uid',$uid)->where('user_type','routine')->count();
    }

    /**
     * @param int $uid
     * @return int
     */
    public static function isUserStatus($uid = 0){
      if(!$uid) return 0;
      $user = User::getUserInfo($uid);
      return $user['status'];
    }

    /**
     * TODO 获取分销编号
     * @param int $uid
     * @return int
     * @throws \think\Exception
     */
    public static function isUserShare($uid = 0){
      if(!$uid) return 0;
      $userQrCodeInfo = RoutineQrcode::getRoutineQrCode($uid,'spread');
      if($userQrCodeInfo) return $userQrCodeInfo->id;
      else return 0;
    }


}