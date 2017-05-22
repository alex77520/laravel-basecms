@extends('web.v1.base.admin')
@section('title', '轮播图/友情链接管理')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('container')
    @parent
	@inject('controller', 'App\Http\Controllers\web\v1\Controller')
    <section class="app-content">
	    @if ($controller->HasLimit('SliderEdit') !== false)
        <div class="row">
			<div class="col-md-3 col-sm-6">
				<a href="{{ route('/slider/add') }}" data-toggle="modal" data-target="#commonModal" class="btn btn-success">新建项目</a>
			</div>
		</div>
		<hr />
		@endif
        <div class="row">
            <!-- new row -->
			@foreach ($classifys as $classify)
                <div class="col-md-3 col-sm-6">
				<div class="widget">
					<header class="widget-header">
                        <h4 class="widget-title">
                            {{ $classify->name }}
							<div class="pull-right">
							@if ($controller->HasLimit('SliderEdit') !== false)
                            <a href="{{ route('/slider/edit',['id'=>$classify->cid]) }}" data-toggle="modal" data-target="#commonModal" >
                                <i class="fa fa-pencil"></i>
                            </a>
							@endif
							@if ($controller->HasLimit('SliderDel') !== false)
							&nbsp;
							<a href="javascript:doDel('{{ $classify->cid }}')" >
								<i class="fa fa-remove"></i>
							</a>
							@endif
							</div>
                        </h4>
					</header><!-- .widget-header -->
					<hr class="widget-separator">
					<div class="widget-body p-h-lg">
						<div class="media">
							<div class="media-body">
                                <h4 class="media-heading">API键 : 
									<b class="text-primary">{{ $classify->key }}</b>
								</h4>
                                <small class="media-meta">{{ $classify->intro }}</small>
								<br />
								<br />
                                <small class="media-meta">
									<a href="{{ route('/slider/items',['cid'=>$classify->cid]) }}" class="btn btn-sm btn-primary" >
										<i class="fa fa-list"></i>&nbsp;&nbsp;列表
									</a>
									&nbsp;
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
			content:"您确定要删除该项目吗？",
			confirm:function(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.post("{{ route('/slider/doDel')}}",{id:id},function(data){
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