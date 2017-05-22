@extends('web.v1.base.admin')
@section('title', '机构管理')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@inject('controller', 'App\Http\Controllers\web\Controller')
@section('container')
    @parent
	<section class="app-content">
		<div class="m-b-lg nav-tabs-horizontal white">
			<div class="row">
				<!-- new row -->
				<div class="col-md-12">
					<div class="widget p-lg">
						<h4 class="m-b-lg">操作日志</h4>
						<p class="m-b-lg docs">
							<div class="btn-group" role="group">
								<a href="javascript:del();" class="btn btn-danger btn-xs">删除选中</a>
							</div>
						</p>
						<table class="table">
							<tbody>
							<tr>
								<th><input type="checkbox" id="checkAll"></th>
								<th>操作人</th>
								<th>详情</th>
								<th>类型</th>
								<th>实体</th>
								<th>时间</th>
								<th>操作</th>
							</tr>
							@foreach ($logs as $log)
							<tr>
								<td><input name="ids[]" type="checkbox" value="{{ $log->id }}"></td>
								<td>{{ $log->account }}&nbsp;@if($log->account == $controller->getWebUserInfo()['account'])<span class="badge bg-success" style="font-size:8px;">管理</span> @endif</td>
								<td>{{ $log->data_main }}</td>
								<td>
                                    @if ( $log->data_action == '0' )
                                        <span class="text-success">创建</span>
                                    @elseif($log->data_action == '1')
                                        <span class="text-info">修改</span>
                                    @else
                                        <span class="text-danger">删除</span>
                                    @endif
                                </td>
								<td>{{ $controller::$LogEnt[$log->data_key]}}</td>
								<td>{{ $log->created_at }}</td>
								<td>
                                    <a href="javascript:doEnable('{{$log->id}}')" class="btn btn-danger btn-xs"><i class="mdi mdi-delete mdi-hc-sm"></i></a>
								</td>
							</tr>
							@endforeach
						</tbody></table>
						{{ $logs->links() }}
					</div><!-- .widget -->
				</div>
			</div><!-- .row -->
		</div>
	</section>
@stop

@section('js')
<script>
	function doEnable(id,status){
		var str = "";
		if(status == '1'){
			str = '您确定要禁用该机构账号吗？';
		}else{
			str = '您确定要启用该机构账号吗？';
		}
		$.confirm({
			content:str,
			confirm:function(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.post("{{ route('/admin/group/setting/enable')}}",{gid:id},function(data){
					if(data.code == '1'){
						Toast(data.data);
						setTimeout(function(){
							window.location.reload();
						},1200);
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
				$.post("{{ route('/admin/organize/log/deletes')}}",{ids:ids},function(data){
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