<?php

namespace app\admin\controller\recycle;

use service\FormBuilder as Form;
use service\UtilService as Util;
use service\JsonService as Json;
use think\Request;
use think\Url;
use app\admin\model\recycle\UserBill as UserBillModel;
use app\admin\controller\AuthController;

/**
 * 回收账单  控制器
 */
class UserBill extends AuthController
{

    /**
     * 显示资源列表
     * @return \think\Response
     */
    public function index()
    {
        $params = Util::getMore([
            ['keyword','']
        ],$this->request);
        $this->assign(UserBillModel::systemPage($params));
        $this->assign(compact('params'));
        return $this->fetch();
    }

}
