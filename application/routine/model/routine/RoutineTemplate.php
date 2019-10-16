<?php
namespace  app\routine\model\routine;

use app\routine\model\store\StoreOrder;
use app\routine\model\user\RoutineUser;
use app\routine\model\user\WechatUser;
use service\RoutineTemplateService;
use service\ProgramTemplateService;

/**
 * 小程序模板消息
 * Class RoutineTemplate
 * @package app\routine\model\routine
 */
class RoutineTemplate{
    /**
     * 退款成功发送消息
     * @param array $order
     */
    public static function sendOrderRefundSuccess($order = array()){
        $formId = RoutineFormId::getFormIdOne($order['uid']);
        $data['keyword1']['value'] =  $order['order_id'];
        $data['keyword2']['value'] =  date('Y-m-d H:i:s',time());
        $data['keyword3']['value'] =  $order['pay_price'];
        if($order['pay_type'] == 'yue') $data['keyword4']['value'] =  '余额支付';
        else if($order['pay_type'] == 'weixin') $data['keyword4']['value'] =  '微信支付';
        else if($order['pay_type'] == 'offline') $data['keyword4']['value'] =  '线下支付';
        $data['keyword5']['value'] = '已成功退款';
        RoutineFormId::delFormIdOne($formId);
        ProgramTemplateService::sendTemplate(WechatUser::getOpenId($order['uid']),RoutineTemplateService::ORDER_REFUND_SUCCESS,$data, $formId);
    }
    /**
     * 用户申请退款给管理员发送消息
     * @param array $order
     * @param string $refundReasonWap
     * @param array $adminList
     */
    public static function sendOrderRefundStatus($order = array(),$refundReasonWap = '',$adminList = array()){
        $data['keyword1']['value'] =  $order['order_id'];
        $data['keyword2']['value'] =  $refundReasonWap;
        $data['keyword3']['value'] =  date('Y-m-d H:i:s',time());
        $data['keyword4']['value'] =  $order['pay_price'];
        $data['keyword5']['value'] =  '原路返回';
        foreach ($adminList as $uid){
            $formId = RoutineFormId::getFormIdOne($order['uid']);
            if($formId){
                RoutineFormId::delFormIdOne($formId);
                ProgramTemplateService::sendTemplate(WechatUser::getOpenId($uid),RoutineTemplateService::ORDER_REFUND_STATUS,$data, $formId);
            }
        }
    }
    /**
     * 砍价成功通知
     * @param array $bargain
     * @param array $bargainUser
     * @param int $bargainUserId
     */
    public static function sendBargainSuccess($bargain = array(),$bargainUser  = array(),$bargainUserId = 0){
        $data['keyword1']['value'] =  $bargain['title'];
        $data['keyword2']['value'] =  $bargainUser['bargain_price'];
        $data['keyword3']['value'] =  $bargainUser['bargain_price_min'];
        $data['keyword4']['value'] =  $bargainUser['price'];
        $data['keyword5']['value'] =  $bargainUser['bargain_price_min'];
        $data['keyword6']['value'] =  '恭喜您，已经砍到最低价了';
        $formId = RoutineFormId::getFormIdOne($bargainUserId);
        if($formId){
            $dataFormId['formId'] = $formId;
            ProgramTemplateService::sendTemplate(WechatUser::getOpenId($bargainUser['uid']),RoutineTemplateService::BARGAIN_SUCCESS,$data, $formId);
        }
    }
    /**
     * 订单支付成功发送模板消息
     * @param string $formId
     * @param string $orderId
     */
    public static function sendOrderSuccess($formId = '',$orderId = ''){
        if($orderId == '') return ;
        $order = StoreOrder::where('order_id',$orderId)->find();
        if($formId == '') $formId = RoutineFormId::getFormIdOne($order['uid']);
        $data['keyword1']['value'] =  $orderId;
        $data['keyword2']['value'] =  date('Y-m-d H:i:s',time());
        $data['keyword3']['value'] =  '已支付';
        $data['keyword4']['value'] =  $order['pay_price'];
        if($order['pay_type'] == 'yue') $data['keyword5']['value'] =  '余额支付';
        else if($order['pay_type'] == 'weixin') $data['keyword5']['value'] =  '微信支付';
        else if($order['pay_type'] == 'offline') $data['keyword5']['value'] =  '线下支付';
        RoutineFormId::delFormIdOne($formId);
        $link = 'pages/orders-con/orders-con?uid='.$order['uid'].'&order_id='.$orderId;
        ProgramTemplateService::sendTemplate(WechatUser::getOpenId($order['uid']),RoutineTemplateService::ORDER_PAY_SUCCESS,$data, $formId,$link);
    }

    /** 订单发货模版消息
     * @param $order
     * @return bool
     */
    public static function sendOrderPostage($order){
        $routine_openid = WechatUser::getOpenId($order['uid']);
        if(!$routine_openid) return true;
        $tempdata = [
            'keyword1' =>  $order['order_id'],
            'keyword2' =>  $order['delivery_name'],
            'keyword3' =>  $order['delivery_id'],
            'keyword4' =>  date('Y-m-d H:i:s',time()),
            'keyword5' =>  '您的商品已经发货请注意查收',
        ];
        $formId = RoutineFormId::getFormIdOne($order['uid']);
        if($formId){
            RoutineFormId::delFormIdOne($formId);
            $link = 'pages/orders-con/orders-con?uid='.$order['uid'].'&order_id='.$order['order_id'];
            ProgramTemplateService::sendTemplate($routine_openid,RoutineTemplateService::ORDER_POSTAGE_SUCCESS,$tempdata, $formId, $link);
        }
    }
    /** 订单送货模版消息
     * @param $order
     * @return bool
     */
    public static function sendOrderDelivery($order){
        $routine_openid = WechatUser::getOpenId($order['uid']);
        if(!$routine_openid) return true;
        $tempdata = [
            'keyword1' =>  $order['order_id'],
            'keyword2' =>  $order['delivery_name'],
            'keyword3' =>  $order['delivery_id'],
            'keyword4' =>  date('Y-m-d H:i:s',time()),
            'keyword5' =>  '您的商品已经在配送请注意查收',
        ];
        $formId = RoutineFormId::getFormIdOne($order['uid']);
        if($formId){
            RoutineFormId::delFormIdOne($formId);
            $link = 'pages/orders-con/orders-con?uid='.$order['uid'].'&order_id='.$order['order_id'];
            ProgramTemplateService::sendTemplate($routine_openid,RoutineTemplateService::ORDER_DELIVER_SUCCESS,$tempdata, $formId, $link);
        }
    }


}