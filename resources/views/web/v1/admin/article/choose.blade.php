@extends('web.v1.base.admin')
@section('title', '选择发布类型')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@inject('controller', 'App\Http\Controllers\web\Controller')
@section('container')
    @parent
	<section class="app-content">
        <div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="text-center">
					<h3 class="m-0 m-h-md">请选择要创建的文章类型</h3>
					{{-- <p class="m-b-lg">We provide the best customer service over there</p> --}}
					{{-- <a href="#" class="btn btn-primary rounded btn-rounded m-b-xl" style="min-width: 160px;" role="button">Start Now</a> --}}
				</div>
			</div>
		</div>
        <div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="row no-gutter">
					<!-- price column -->
					<div class="col-sm-4">
						<div class="box price-box">
							<div class="box-head text-center p-lg">
								<small class="text-muted">普通模式</small>
								{{-- <h4 class="box-title m-t-0">Free! / <small class="text-muted fz-sm">1 month</small></h4> --}}
							</div>
							<div class="box-body text-center p-h-lg">
                                <ul>
									<li><strong>内容一次性编辑完成，适合简单的新闻、资讯发布；</strong></li>
									<li><strong>文章内容可排版；</strong></li>
								</ul>
							</div>
							<div class="box-footer p-lg">
								<a href="{{ route('/admin/article/create/single') }}" class="btn btn-primary rounded" role="button">创建</a>
							</div>
						</div>
					</div><!-- END price-column -->
					<!-- price column -->
					<div class="col-sm-4">
						<div class="box price-box">
							<div class="box-head text-center p-lg">
								<small class="text-muted">图文模式</small>
							</div>
							<div class="box-body text-center p-h-lg">
                                <ul>
									<li><strong>根据需要添加段落，每条段落可配一张图；</strong></li>
									<li><strong>段落中不可插图，没有排版格式；</strong></li>
								</ul>
							</div>
							<div class="box-footer p-lg">
								<a href="{{ route('/admin/article/create/image-text') }}" class="btn btn-primary rounded" role="button">创建</a>
							</div>
						</div>
					</div><!-- END price-column -->

					<!-- price column -->
					<div class="col-sm-4">
						<div class="box price-box">
							<div class="box-head text-center p-lg">
								<small class="text-muted">MarkDown模式</small>
							</div>
							<div class="box-body text-center p-h-lg">
                                <ul>
									<li><strong>内容一次性编辑完成，适合简单的新闻、资讯发布；</strong></li>
									<li><strong>采用 MarkDown 进行排版；</strong></li>
								</ul>
							</div>
							<div class="box-footer p-lg">
								<a href="{{ route('/admin/article/create/markdown') }}" class="btn btn-primary rounded" role="button">创建</a>
							</div>
						</div>
					</div><!-- END price-column -->
				</div>
			</div>
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
			Toast('未选中一条数据');
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
						$.alert({
							content:'成功删除 '+data.data+' 条记录',
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