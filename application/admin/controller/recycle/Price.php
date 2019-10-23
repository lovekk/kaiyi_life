<?php

namespace app\admin\controller\recycle;

use service\FormBuilder as Form;
use service\UtilService as Util;
use service\JsonService as Json;
use think\Request;
use think\Url;
use app\admin\model\recycle\Price as PriceModel;
use app\admin\controller\AuthController;

/**
 * 回收价格 控制器
 */
class Price extends AuthController
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
        $this->assign(PriceModel::systemPage($params));
        $this->assign(compact('params'));
        return $this->fetch();
    }


    /**
     * 显示创建资源表单页.
     * @return \think\Response
     */
    public function create($cid = 0)
    {
        $formbuider = [
            Form::input('name','物品名称')->required('物品名称必填'),
            Form::number('money','价格',0)->required('价格必填'),
            Form::input('unit','单位[例:元/公斤2]')->required('单位必填'),
            Form::number('sort','排序',0),
        ];
        $form = Form::make_post_form('添加回收物品',$formbuider,Url::build('save'),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }


    /**
     * 保存新建的资源
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = Util::postMore([
            'name',
            'money',
            'unit',
            'sort',
        ],$request);
        if(!$data['name']) return Json::fail('请输入物品名称');
        if(!$data['money']) return Json::fail('请输入价格');
        if(!$data['unit']) return Json::fail('请输入单位');
        PriceModel::set($data);
        return Json::successful('添加回收物品成功!');
    }


    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $menu = PriceModel::get($id);
        if(!$menu) return Json::fail('数据不存在!');
        $formbuider = [
            Form::input('name','物品名称',$menu['name']),
            Form::number('money','价格',$menu['money']),
            Form::input('unit','单位[例:元/公斤，毛/个]',$menu['unit']),
            Form::number('sort','排序',$menu['sort']),

        ];
        $form = Form::make_post_form('添加回收物品',$formbuider,Url::build('update',array('id'=>$id)),2);
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
            'name',
            'money',
            'unit',
            'sort',
        ],$request);
        if(!$data['name']) return Json::fail('请输入物品名称');
        if(!$data['money']) return Json::fail('请输入价格');
        if(!$data['unit']) return Json::fail('请输入单位');
        if(!PriceModel::get($id)) return Json::fail('编辑的记录不存在!');
        PriceModel::edit($data,$id);
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
        $res = PriceModel::destroy($id);
        if(!$res)
            return Json::fail(ExpressModel::getErrorInfo('删除失败,请稍候再试!'));
        else
            return Json::successful('删除成功!');
    }

}
