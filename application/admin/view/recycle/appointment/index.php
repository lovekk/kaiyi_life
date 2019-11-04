{extend name="public/container"}
{block name="content"}
<div class="row">
    <div class="col-sm-12">
        <div class="ibox">
<!--            <div class="ibox-title">-->
<!--                <button type="button" class="btn btn-w-m btn-primary" onclick="$eb.createModalFrame(this.innerText,'{:Url('create')}')">添加预约</button>-->
<!--                <div class="ibox-tools">-->
<!--                </div>-->
<!--            </div>-->
            <div class="ibox-content">
                <div class="row">
                    <div class="m-b m-l">
                        <form action="" class="form-inline">

                            <div class="input-group">
                                <input type="text" name="keyword" value="{$params.keyword}" placeholder="昵称/姓名/区域/上门日期" class="input-sm form-control"> <span class="input-group-btn">
                                      <button type="submit" class="btn btn-sm btn-primary"> <i class="fa fa-search" ></i>搜索</button> </span>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped  table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">编号</th>
                            <th class="text-center">昵称</th>
                            <th class="text-center">姓名</th>
                            <th class="text-center">手机</th>
                            <th class="text-center">地址</th>
                            <th class="text-center">主要物品</th>
                            <th class="text-center">上门时间</th>
                            <th class="text-center">区域</th>
                            <th class="text-center">备注</th>
                            <th class="text-center">状态</th>
                            <th class="text-center">下单时间</th>
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody class="">
                        {volist name="list" id="vo"}
                        <tr>
                            <td class="text-center">
                                {$vo.id}
                            </td>
                            <td class="text-center">
                                {$vo.nickname}
                            </td>
                            <td class="text-center" >
                                {$vo.name}
                            </td>
                            <td class="text-center" >
                                {$vo.phone}
                            </td>
                            <td class="text-center" >
                                {$vo.area} {$vo.fulladdress}
                            </td>
                            <td class="text-center" >
                                {$vo.goods}
                            </td>
                            <td class="text-center" >
                                {$vo.appdate} {$vo.apptime}
                            </td>
                            <td class="text-center" >
                                {$vo.school_name}
                            </td>
                            <td class="text-center" >
                                {$vo.remark}
                            </td>
<!--                            状态1预约2已回收3已转账-->
                            <td class="text-center" >
                                {if $vo.status==3}已转账
                                {elseif $vo.status==2 /}已回收
                                {else /}预约
                                {/if}

                            </td>
                            <td class="text-center" >
                                <?php echo date('Y-m-d H:i',$vo['add_time']); ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-info btn-xs" type="button"  onclick="$eb.createModalFrame('编辑','{:Url('edit',array('id'=>$vo['id']))}')"><i class="fa fa-paste"></i> 编辑</button>
                                <button class="btn btn-warning btn-xs" data-url="{:Url('delete',array('id'=>$vo['id']))}" type="button"><i class="fa fa-warning"></i> 删除
                                </button>
                            </td>
                        </tr>
                        {/volist}
                        </tbody>
                    </table>
                </div>
                {include file="public/inner_page"}
            </div>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script>
    $('.btn-warning').on('click',function(){
        window.t = $(this);
        var _this = $(this),url =_this.data('url');
        $eb.$swal('delete',function(){
            $eb.axios.get(url).then(function(res){
                console.log(res);
                if(res.status == 200 && res.data.code == 200) {
                    $eb.$swal('success',res.data.msg);
                    _this.parents('tr').remove();
                }else
                    return Promise.reject(res.data.msg || '删除失败')
            }).catch(function(err){
                $eb.$swal('error',err);
            });
        })
    });
</script>
{/block}
