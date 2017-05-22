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
						<h4 class="m-b-lg">机构列表</h4>
						<p class="m-b-lg docs">
                            禁用状态: 已发布的文章、信息不受影响，禁用后无法发布新的信息
						</p>
						<table class="table">
							<tbody>
							<tr>
								<th>#</th>
								<th>名称</th>
								<th>简介</th>
								<th>状态</th>
								<th>发文审核</th>
								<th>员工数(现有/最大)</th>
								{{-- <th>资源可用(MB)</th> --}}
								<th>操作</th>
							</tr>
							@foreach ($groups as $group)
							<tr>
								<td>
                                    <div class="avatar avatar-sm avatar-circle">
                                        @if($group->avatar != null)
                                        <img class="img-responsive" src="{{ $group->avatar }}" alt="avatar"/>
                                        @else 
                                        <img class="img-responsive" src="{{ URL::asset('/web/v1/assets/images/default.jpg') }}" alt="无"/>
                                        @endif
                                    </div>
                                </td>
								<td>{{ $group->name }}&nbsp;@if($group->recommend =='1')<span class="badge bg-success" style="font-size:8px;">推荐</span> @endif</td>
								<td>{{ $group->intro }}</td>
								<td>
								@if ( $group->status == '1' )
									<i class="fa fa-toggle-on text-primary"></i>
								@else
									<i class="fa fa-toggle-off"></i>
								@endif
                                </td>
								<td>
                                    @if ( $group->post_audit == '1' )
                                        <span class="text-warning">需审核</span>
                                    @else
                                        <span class="text-success">不审核</span>
                                    @endif
                                </td>
								<td>{{ $group->GroupUsers->count() ."/". $group->user_num }}</td>
								{{-- <td>{{ $group->resource_size }}</td> --}}
								<td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="mdi mdi-hc-lg mdi-settings"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                        @if ($controller->HasLimit('GroupEdit') !== false)
                                            <li><a href="{{ route('/admin/group/edit',['gid'=>$group->gid]) }}" data-toggle="modal" data-target="#commonModal" >基本信息</a></li>
                                        @endif
                                        @if ($controller->HasLimit('GroupSettingSize') !== false)
                                            <li><a href="{{ route('/admin/group/setting/size',['gid'=>$group->gid]) }}" data-toggle="modal" data-target="#commonModal" >资源分配</a></li>
                                        @endif
                                        @if ($controller->HasLimit('GroupSettingLimit') !== false)
                                            <li><a href="{{ route('/admin/group/setting/limit',['gid'=>$group->gid]) }}" data-toggle="modal" data-target="#commonModal" >权限设置</a></li>
                                        @endif
                                        @if ($controller->HasLimit('GroupSettingEnable') !== false)
                                            <li>
                                                @if($group->status == '1')
                                                    <a href="javascript:doEnable('{{$group->gid}}','{{$group->status}}')" class="text-danger">禁用</a>
                                                @else 
                                                    <a href="javascript:doEnable('{{$group->gid}}','{{$group->status}}')" class="text-success">启用</a>
                                                @endif
                                            </li>
                                        @endif
                                        @if ($controller->HasLimit('GroupSettingRecommend') !== false)
                                            <li role="separator" class="divider"></li>
                                            <li>
                                                @if($group->recommend == '1')
                                                    <a href="javascript:doRecommend('{{$group->gid}}','{{$group->status}}')" class="text-danger">取消推荐</a>
                                                @else 
                                                    <a href="javascript:doRecommend('{{$group->gid}}','{{$group->status}}')" class="text-success">设为推荐</a>
                                                @endif
                                            </li>
                                        @endif
                                        </ul>
                                    </div>
								</td>
							</tr>
							@endforeach
						</tbody></table>
						{{ $groups->links() }}
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
	function doRecommend(id,status){
		var str = "";
		if(status == '1'){
			str = '您确定要将该机构设置为“推荐”吗？';
		}else{
			str = '您确定要取消该机构的“推荐”位吗？';
		}
		$.confirm({
			content:str,
			confirm:function(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.post("{{ route('/admin/group/setting/recommend')}}",{gid:id},function(data){
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