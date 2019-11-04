<?php

namespace app\admin\controller\recycle;

use service\FormBuilder as Form;
use service\UtilService as Util;
use service\JsonService as Json;
use think\Request;
use think\Url;
use app\admin\model\recycle\Appointment as AppointmentModel;
use app\admin\controller\AuthController;

/**
 * 回收预约  控制器
 */
class Appointment extends AuthController
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
        $this->assign(AppointmentModel::systemPage($params));
        $this->assign(compact('params'));
        return $this->fetch();
    }


    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $menu = AppointmentModel::get($id);
        if(!$menu) return Json::fail('数据不存在!');
        $formbuider = [
            Form::input('nickname','用户名',$menu['nickname']),
            Form::input('area','用户地址',$menu['area']),
            Form::input('fulladdress','详细地址',$menu['fulladdress']),
            // 状态1预约2完成3已转账
            Form::radio('status','状态',$menu['status'])->options([['value'=>1,'label'=>'预约'],['value'=>2,'label'=>'已回收'],['value'=>3,'label'=>'已转账']])
        ];
        $form = Form::make_post_form('回收预约',$formbuider,Url::build('update',array('id'=>$id)),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }


    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $data = Util::postMore([
            'nickname',
            'area',
            'fulladdress',
            ['status',1]
        ],$request);
        if(!$data['nickname']) return Json::fail('请输入用户名');
        if(!$data['area']) return Json::fail('请输入用户地址');
        if(!$data['fulladdress']) return Json::fail('请输入详细地址');
        if(!$data['status']) return Json::fail('请输入状态');
        if(!AppointmentModel::get($id)) return Json::fail('编辑的记录不存在!');
        AppointmentModel::edit($data,$id);
        return Json::successful('修改成功!');
    }


    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if(!$id) return $this->failed('参数错误，请重新打开');
        $res = AppointmentModel::destroy($id);
        if(!$res)
            return Json::fail(ExpressModel::getErrorInfo('删除失败,请稍候再试!'));
        else
            return Json::successful('删除成功!');
    }

}
