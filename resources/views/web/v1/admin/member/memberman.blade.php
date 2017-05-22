@extends('web.v1.base.admin')
@section('title', '会员管理')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('container')
    @parent
	<section class="app-content">
		<div class="m-b-lg nav-tabs-horizontal white">
			<p class="m-b-xl">
			</p>
			<div class="row">
				<!-- new row -->
				<div class="col-md-12">
					<div class="widget p-lg">
						<h4 class="m-b-lg">会员列表</h4>
						<p class="m-b-lg docs">
							{{-- Use contextual classes (e.g <code>.success</code>) to color table rows or individual cells. --}}
						</p>
						<table class="table">
							<tbody>
							<tr>
								<th>#</th>
								<th>手机号</th>
								<th>昵称</th>
								<th>微信</th>
								<th>注册时间</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
							@foreach ($members as $member)
							<tr>
								<td>
								<div class="avatar avatar-md avatar-circle">
									<img src="{{ $member->avatar }}" class="img-responsive">
								</div>
						</td>
								<td>{{ $member->mobile }}</td>
								<td>{{ $member->nickname }}</td>
								<td><a href="#">{{ $member->wechat_openid }}</a></td>
								<td>{{ date('Y-m-d H:i',$member->signtime) }}</td>
								<td>
								@if ( $member->status == '1' )
									<a href="javascript:doEditStatus('{{ $member->member_id }}','1')" title="正常"><i class="fa fa-toggle-on"></i></a>
								@else
									<a href="javascript:doEditStatus('{{ $member->member_id }}','0')" title="冻结"><i class="fa fa-toggle-off"></i></a>
								@endif
								</td>
								<td>
								<a href="{{ route('/member/password',['id'=>$member->member_id])}}" data-toggle="modal" data-target="#commonModal"><i class="fa fa-key"></i></a>&nbsp;&nbsp;&nbsp;
								</td>
							</tr>
							@endforeach
						</tbody></table>
						{{ $members->links() }}
					</div><!-- .widget -->
				</div>
			</div><!-- .row -->
		</div>
	</section>
@stop

@section('js')
<script>
	function doEditStatus(id,status){
		var str = "";
		if(status == '1'){
			str = '您确定要冻结该账户吗？';
		}else{
			str = '您确定要解冻该账户吗？';
		}
		$.confirm({
			content:str,
			confirm:function(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.post("{{ route('/member/doEditStatus')}}",{id:id},function(data){
					if(data.code == '1'){
						$.alert({
							content:data.data,
							cofirmButtonClass:"btn-success",
							confirm:function(){
								window.location.reload();
							}
						});
					}else{
						$.alert(data.data);
					}
				},'json');
			}
		})
	}
</script>
@stop