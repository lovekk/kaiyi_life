{extend name="public/container"}
{block name="content"}
<div class="row">
    <div class="col-sm-12">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="m-b m-l">
                        <form action="" class="form-inline">

                            <div class="input-group">
                                <input type="text" name="keyword" value="{$params.keyword}" placeholder="账单标题/说明" class="input-sm form-control"> <span class="input-group-btn">
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
                            <th class="text-center">用户uid</th>
                            <th class="text-center">支出/获得</th>
                            <th class="text-center">账单标题</th>
                            <th class="text-center">明细种类</th>
                            <th class="text-center">明细类型</th>
                            <th class="text-center">明细数字</th>
                            <th class="text-center">剩余</th>
                            <th class="text-center">备注</th>
                            <th class="text-center">添加时间</th>
<!--                            <th class="text-center">操作</th>-->
                        </tr>
                        </thead>
                        <tbody class="">
                        {volist name="list" id="vo"}
                        <tr>
                            <td class="text-center">
                                {$vo.id}
                            </td>
                            <td class="text-center">
                                {$vo.uid}
                            </td>
                            <td class="text-center" >
                                {if $vo.pm==0}支出
                                {elseif $vo.pm==1 /}获得
                                {else /}err
                                {/if}
                            </td>
                            <td class="text-center" >
                                {$vo.title}
                            </td>
                            <td class="text-center" >
                                {$vo.category}
                            </td>
                            <td class="text-center" >
                                {$vo.type}
                            </td>
                            <td class="text-center" >
                                {$vo.number}
                            </td>
                            <td class="text-center" >
                                {$vo.balance}
                            </td>
                            <td class="text-center" >
                                {$vo.mark}
                            </td>
                            <td class="text-center" >
                                <?php echo date('Y-m-d H:i',$vo['add_time']); ?>
                            </td>
<!--                            <td class="text-center">-->
<!--                                <button class="btn btn-info btn-xs" type="button"  onclick="$eb.createModalFrame('编辑','{:Url('edit',array('id'=>$vo['id']))}')"><i class="fa fa-paste"></i> 编辑</button>-->
<!--                                <button class="btn btn-warning btn-xs" data-url="{:Url('delete',array('id'=>$vo['id']))}" type="button"><i class="fa fa-warning"></i> 删除-->
<!--                                </button>-->
<!--                            </td>-->
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
