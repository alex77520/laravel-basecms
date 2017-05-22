@extends('web.v1.base.admin')
@section('title', '分类管理')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@inject('controller', 'App\Http\Controllers\web\Controller')
@section('container')
    @parent
	<section class="app-content">
		<div class="row">
			<div class="col-md-3 col-sm-12">
				<div class="list-group">
					<h4>一级分类</h4>
					<a href="{{ route('/admin/posts/classify/add') }}" data-toggle="modal" data-target="#commonModal" class="list-group-item text-color">
						<i class="fa fa-plus m-r-sm"></i> 添加新分类
					</a>
					@if($classifys != null)
						@foreach($classifys as $classify)
								<a href="{{ route('/admin/posts/classify' ,['id' => $classify->pc_id ]) }}" class="list-group-item">
									<span>{{ $classify->name }}</span>
									<span class="badge badge-primary">{{ $classify->key }}</span>
								</a>
						@endforeach
					@endif
				</div>
			</div>


			<div class="col-md-9 col-sm-12">
				@if($classifyInfo != null)
					<div class="row">
						<div class="col-md-12">
							<div class="m-b-lg">								
								<div class="btn-group" role="group">
									<a href="{{ route('/admin/posts/classify/add' ,['id' => $classifyInfo->pc_id ]) }}" data-toggle="modal" data-target="#commonModal" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>&nbsp;添加子类</a>
									<a href="{{ route('/admin/posts/classify/edit' ,['id' => $classifyInfo->pc_id ]) }}" data-toggle="modal" data-target="#commonModal" type="button" class="btn btn-info btn-sm"><i class="fa fa-edit"></i>&nbsp;修改分类信息</a>
									<a href="javascript:doDel('{{ $classifyInfo->pc_id }}')" type="button" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i>&nbsp;删除</a>
								</div>
							</div>
						</div>
					</div>	
					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h4 class="panel-title">{{ $classifyInfo->name }}</h4>
						</div>
						<div class="panel-body">
							<p>{{ $classifyInfo->intro }}</p>
						</div>
						<table class="table">
							<thead>
								<th>#</th>
								<th>子类名称</th>
								<th>API键</th>
								<th>状态</th>
								<th>操作</th>
							</thead>
							<tbody>
								@if($classifyInfo->children != null)
									@foreach($classifyInfo->children as $son)
									<tr class="son-{{ $son->pc_id }}">
										<td>{{ $son->pc_id }}</td>
										<td>{{ $son->name }}</td>
										<td>{{ $son->key }}</td>
										<td>
											@if($son->show == '1')
												<span class="text-success">启用</span>
											@else
												<span class="text-danger">禁用</span>
											@endif
										</td>
										<td>
											<div class="btn-group">
												<button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<i class="mdi mdi-hc-lg mdi-settings"></i>
												</button>
												<ul class="dropdown-menu">
												@if ($controller->HasLimit('PostClassifyEdit') !== false)
													<li><a href="{{ route('/admin/posts/classify/edit' , ['id' => $son->pc_id ]) }}" data-toggle="modal" data-target="#commonModal" >基本信息</a></li>
												@endif
												@if ($controller->HasLimit('PostClassifySetting') !== false)
													<li><a href="{{ route('/admin/posts/classify/edit' , ['id' => $son->pc_id ]) }}" data-toggle="modal" data-target="#commonModal" >批量调整</a></li>
												@endif
												@if ($controller->HasLimit('PostClassifyDelete') !== false)
													<li role="separator" class="divider"></li>
													<li>
														<a href="javascript:doDel('{{ $son->pc_id }}');" class="text-danger">删除</a>
													</li>
												@endif
												</ul>
											</div>
										</td>
									</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</div>
				@endif
			</div><!-- END column -->
		</div><!-- .row -->
	</section>
@stop

@section('js')
<script>
function doDel(id){
	@if($classifyInfo != null)
	var fatherId = "{{ $classifyInfo->pc_id }}";
	@endif
		$.confirm({
			content:"您确定要删除该分类？",
			confirm:function(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.post("{{ route('/admin/posts/classify/del')}}",{id:id},function(data){
					if(data.code == '1'){
						Toast(data.data);
						setTimeout(function(){
							@if($classifyInfo != null)
							if(fatherId == id){
								window.location.href="{{ route('/admin/posts/classify') }}";
							}else{
								$('.son-'+id).remove();
							}
							@else 
								$('.son-'+id).remove();
								// window.location.reload();
							@endif
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