@extends('web.v1.base.admin')
@section('title', '组内信管理')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@inject('controller', 'App\Http\Controllers\web\Controller')
@section('container')
    @parent
	<section class="app-content">
		<div class="row">
			<div class="col-md-2">
				<div class="app-action-panel" id="inbox-action-panel">
					<div class="action-panel-toggle" data-toggle="class" data-target="#inbox-action-panel" data-class="open">
						<i class="fa fa-chevron-right"></i>
						<i class="fa fa-chevron-left"></i>
					</div><!-- .app-action-panel -->
					<div class="app-actions-list scrollable-container ps-container ps-theme-default" data-ps-id="ba4c682c-c426-d139-2fde-3d9a24ab539f">
						<div class="list-group">
							<a href="{{route('/admin/organize/notice/create')}}" class="text-color list-group-item"><i class="m-r-sm mdi mdi-note-plus"></i>新建组内信</a>
						</div><!-- .list-group -->
						<hr class="m-0 m-b-md" style="border-color: #ddd;">
						<!-- mail label list -->
						<div class="list-group">
							<h4>筛选</h4>
                            <hr />
                            <form action="{{route('/admin/organize/notice')}}" method="get">
                                <div class="form-group">
                                    <label for="level">等级</label>
                                    <select name="level" id="level" class="form-control" data-plugin="select2">
                                        <option value="0" @if(isset($search['level'])) @if($search['level'] == '0') selected="selected" @endif @endif>不限</option>
                                        <option value="1" @if(isset($search['level'])) @if($search['level'] == '1') selected="selected" @endif @endif>1级</option>
                                        <option value="2" @if(isset($search['level'])) @if($search['level'] == '2') selected="selected" @endif @endif>2级</option>
                                        <option value="3" @if(isset($search['level'])) @if($search['level'] == '3') selected="selected" @endif @endif>3级</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="search">关键字</label>
                                     @if(isset($search['key'])) 
                                        <input type="text" name="key" placeholder="标题/内容" class="form-control" value="{{$search['key']}}">
                                        @else
                                        <input type="text" name="key" placeholder="标题/来源" class="form-control" value="">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block"><i class="mdi mdi-magnify"></i> 检索</button>
                                </div>
                            </form>
						</div><!-- .list-group -->
					<div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; right: 3px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div><!-- .app-actions-list -->
				</div><!-- .app-action-panel -->
			</div><!-- END column -->

			<div class="col-md-10">
				<div class="row">
					<div class="col-md-12">
						<div class="m-b-lg">
                            <a href="javascript:del();" class="btn btn-danger"><i class="mdi mdi-delete"></i> 删除</a>
						</div>
					</div>
				</div>

				<div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <h4 class="m-b-lg">文章</h4>
                            <p class="m-b-lg docs">
                            </p>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>标题</th>
                                        <th>推送者</th>
                                        <th>等级</th>
                                        <th>面向对象</th>
                                        <th>推送时间</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($notices as $notice)
                                <tr>
                                    <td>
                                        <input type="checkbox" value="{{$notice->notice_id}}" name="ids[]">
                                    </td>
                                    <td>{{$notice->title}}</td>
                                    <td>{{$notice->User->account}}</td>
                                    <td>
                                       {{$notice->level}}
                                    </td>
                                    <td>
                                        @if($notice->type == '1')
                                            整组
                                        @else 
                                            部分用户
                                        @endif
                                    </td>
                                    <td>
                                       {{$notice->created_at}}
                                    </td>
                                    <td>
                                        <div class="btn-group dropup">
                                            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-hc-lg mdi-settings"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ route('/admin/organize/notice/intro',['id'=>$notice->notice_id]) }}" data-toggle="modal" data-target="#commonModal-lg">详细信息</a></li>
                                                <li><a href="{{ route('/admin/organize/notice/intro/users',['id'=>$notice->notice_id]) }}" data-toggle="modal" data-target="#commonModal">阅读详情</a></li>
                                                <li>
                                                    <a href="javascript:doDel('{{$notice->notice_id}}')" class="text-danger">删除</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody></table>
                            {{ $notices->links() }}
                        </div><!-- .widget -->
                    </div>
				</div>
			</div><!-- END column -->
		</div><!-- .row -->
	</section>
@stop

@section('js')
<script>
function doDel(id){
    $.confirm({
        content:"您确定要删除这条数据吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/organize/notice/delete')}}",{notice_id:id},function(data){
                if(data.code == '1'){
                    Toast('成功删除 '+data.data+' 条记录');
                    setTimeout(function(){
                        window.location.reload();
                    },1500);
                }else{
                    $.alert(data.data);
                }
            },'json');
        }
    })
}
function del(){
    var cnt = 0;
    var ids = "";
    $("input[name='ids[]']").each(function(){
        if($(this).is(':checked')){
            ids += $(this).val()+",";
            cnt++;
        }
    });
    if(cnt <= 0){
        Toast('未选中数据');
        return;
    }
    //提交
    $.confirm({
        content:"你确定要删除这 "+cnt+" 条数据吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/organize/notice/deletes')}}",{ids:ids},function(data){
                if(data.code == '1'){
                    Toast('成功删除 '+data.data+' 条记录');
                    setTimeout(function(){
                        window.location.reload();
                    },1500);
                }else{
                    $.alert(data.data);
                }
            },'json');
        }
    })
}
</script>
@stop