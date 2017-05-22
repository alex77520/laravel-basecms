@extends('web.v1.base.admin')
@section('title', '机构管理')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@inject('controller', 'App\Http\Controllers\web\Controller')
@section('container')
    @parent
	<section class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="mail-toolbar m-b-lg">								
                    <div class="btn-group" role="group">
                        <a href="{{ route('/admin/organize/user/add') }}" class="btn btn-default btn-sm" data-toggle="modal" data-target="#commonModal">添加员工</a>
                    </div>
                </div>
            </div>
        </div>
		<div class="m-b-lg nav-tabs-horizontal white">
			<div class="row">
				<!-- new row -->
				<div class="col-md-12">
					<div class="widget p-lg">
						<h4 class="m-b-lg">员工列表</h4>
						<p class="m-b-lg docs">
                            禁用状态: 已发布的文章、信息不受影响，禁用后无法发布新的信息
						</p>
						<table class="table">
							<tbody>
							<tr>
								<th>#</th>
								<th>账号</th>
								<th>昵称</th>
								<th>邮箱</th>
								<th>状态</th>
								<th>最后登录时间</th>
								<th>操作</th>
							</tr>
							@foreach ($users as $user)
							<tr>
								<td>
                                    <div class="avatar avatar-sm avatar-circle">
                                        @if($user->User->avatar != null)
                                        <img class="img-responsive" src="{{ $user->User->avatar }}" alt="avatar"/>
                                        @else 
                                        <img class="img-responsive" src="{{ URL::asset('/web/v1/assets/images/default.jpg') }}" alt="无"/>
                                        @endif
                                    </div>
                                </td>
								<td>{{ $user->User->account }}</td>
								<td>{{ $user->User->nickname }}</td>
								<td>{{ $user->User->email }}</td>
								<td>
								@if ( $user->User->power == '1' )
									<i class="fa fa-toggle-on text-primary"></i>
								@else
									<i class="fa fa-toggle-off"></i>
								@endif
                                </td>
								<td>{{ $user->User->last_login }}</td>
								<td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="mdi mdi-hc-lg mdi-settings"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            {{-- <li><a href="{{ route('/admin/group/edit',['gid'=>$user->User->uid]) }}" data-toggle="modal" data-target="#commonModal" >基本信息</a></li> --}}
                                            <li><a href="{{ route('/admin/organize/user/password',['uid'=>$user->User->uid]) }}" data-toggle="modal" data-target="#commonModal" >重置密码</a></li>
                                            <li><a href="{{ route('/admin/organize/log',['account'=>$user->User->account]) }}">操作日志</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li>
                                                @if($user->User->power == '1')
                                                    <a href="javascript:doEnable('{{$user->User->uid}}','{{$user->User->power}}')" class="text-danger">禁用</a>
                                                @else 
                                                    <a href="javascript:doEnable('{{$user->User->uid}}','{{$user->User->power}}')" class="text-success">启用</a>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
								</td>
							</tr>
							@endforeach
						</tbody></table>
						{{ $users->links() }}
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
			str = '您确定要禁用该账号吗？';
		}else{
			str = '您确定要启用该账号吗？';
		}
		$.confirm({
			content:str,
			confirm:function(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.post("{{ route('/admin/organize/user/enable')}}",{uid:id},function(data){
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
</script>
@stop