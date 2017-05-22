@extends('web.v1.base.admin')
@section('title', '角色管理')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('container')
    @parent
    <section class="app-content">
		<form action="{{ route('/admin/role') }}">
			<div class="row">
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<input type="search" class="form-control promo-search-field" name="s" placeholder="角色名/简介" value="{{ $s }}">
					</div>
				</div>
				<div class="col-xs-12 col-sm-2">
					<input type="submit" class="btn btn-primary btn-block promo-search-submit btn-sm" value="搜索">
				</div>
			</div>
		</form>
        <div class="row">
			<div class="col-md-3 col-sm-6">
				<a href="{{ route('/admin/role/add') }}" data-toggle="modal" data-target="#commonModal" class="btn btn-success btn-sm">新建角色</a>
			</div>
		</div>
		<hr />
        <div class="row">
            <!-- new row -->
            @foreach ($roles as $role)
                <div class="col-md-3 col-sm-6">
				<div class="widget">
					<header class="widget-header">
                        <h4 class="widget-title">
						{{ $role->name }}
						<div class="pull-right">
							<a href="{{ route('/admin/role/edit',['id'=>$role->id]) }}" data-toggle="modal" data-target="#commonModal" ><i class="fa fa-pencil"></i></a> &nbsp;&nbsp;
							<a href="javascript:doDel('{{ $role->id }}')" ><i class="fa fa-remove"></i></a>
						</div>
						</h4>
					</header><!-- .widget-header -->
					<hr class="widget-separator">
					<div class="widget-body p-h-lg">
						<div class="media">
							<div class="media-body">
                                {{-- <h4 class="media-heading">{{ $role->intro }}</h4> --}}
                                {{-- <small class="media-meta">{{ $role->intro }}</small><br /> --}}
								<span class="color:#333;">权限列表:</span><br />
                                <small class="media-meta text-primary">
									@foreach( $role->role_listname as $name) 
										{{ $name }}
									@endforeach
								</small>
							</div>
						</div>
					</div><!-- .widget-body -->
				</div><!-- .widget -->
			</div>
            @endforeach
        </div><!-- .row -->
    </section>
@stop

@section('js')
<script>
	function doDel(id){
		$.confirm({
			content:"您确定要删除该角色，角色下关联的用户关系也将释放！",
			confirm:function(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.post("{{ route('/admin/role/del')}}",{id:id},function(data){
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