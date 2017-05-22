@extends('web.v1.base.admin')
@section('title', '图片管理')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('container')
    @parent
    @inject('controller', 'App\Http\Controllers\web\Controller')
	<section class="app-content">
		<div class="row">
			<div class="col-md-12">
				<div class="mail-toolbar m-b-lg">								
					<div class="btn-group" role="group">
						<a href="{{ route('/admin/resource/upload') }}" class="btn btn-default" data-toggle="modal" data-target="#commonModal">上传文件</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3 col-sm-12">
				<div class="list-group">
					<h4>分类列表</h4>
					<a href="{{ route('/admin/resource/classify/add') }}" data-toggle="modal" data-target="#commonModal" class="list-group-item text-color">
						<i class="fa fa-plus m-r-sm"></i> 添加新分类
					</a>
					@if($cid == null)
					<a href="{{ route('/admin/resource') }}" class="list-group-item active">
					@else
					<a href="{{ route('/admin/resource') }}" class="list-group-item">
					@endif
						<span>所有文件</span>
					</a>
					@if($classifys != null)
						@foreach($classifys as $classify)
							@if($cid == $classify->id)
							<a href="{{ route('/admin/resource' ,['cid' => $classify->id ]) }}" class="list-group-item active">
							@else
							<a href="{{ route('/admin/resource' ,['cid' => $classify->id ]) }}" class="list-group-item">
							@endif
								<span>{{ $classify->name }}</span>
								<span class="badge badge-primary">{{ $classify->Resources->count() }}</span>
							</a>
						@endforeach
					@endif
				</div>
			</div>
			<div class="col-md-9 col-sm-12">
				@if($cid != null)
				<div>
					<div class="btn-group" role="group">
						<a href="{{ route('/admin/resource/classify/edit',['id'=>$cid]) }}" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#commonModal">修改分类</a>
						<a href="javascript:doClassifyDel('{{ $cid }}')" class="btn btn-danger btn-sm">删除</a>
					</div>
				</div>
				<hr />
				@endif
				<table class="table">
					<thead>
					<tr>
						<th>#</th>
						<th>名称</th>
						<th>类型</th>
						<th>大小</th>
						<th>上传时间</th>
						<th>操作</th>
					</tr>
					</thead>
					<tbody>
				@foreach ( $resources as $file)
				<tr>
					<td>
						<div class="avatar avatar-md">
							@if(in_array($file->type,['png','jpg','gif','jpeg']))
								<a href="{{ $controller->ResourcePath($file->path,$file->filename) }}" data-lightbox="gallery-2" data-title="{{ $file->name }}">
									<img class="img-responsive" src="{{ $controller->ResourcePath($file->path,$file->filename) }}" >
								</a>
								{{-- <img src="{{ $controller->ResourcePath($file->path,$file->filename) }}" class="img-responsive"> --}}
							@else
								<img src="" class="img-responsive">
							@endif
						</div>
					</td>
					<td>{{ $file->name }}</td>
					<td>{{ $file->type }}</td>
					<td><span title="{{ $controller->getSize($file->size,false) }}">{{ $controller->getSize($file->size) }}</span></td>
					<td>{{ $file->created_at }}</td>
					<td><a href="{{ route('/admin/resource/edit',['id'=>$file->id]) }}" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#commonModal"><i class="fa fa-pencil"></i></a>
					<a href="javascript:doDel('{{ $file->id }}')" class="btn btn-sm btn-danger"><i class="mdi mdi-hc-lg mdi-delete"></i></a></td>
					</td>
				</tr>
				@endforeach
				</tbody>
				</table>
			</div>
			{{ $resources->links() }}
		</div>
	</section><!-- #dash-content -->

@stop

@section('js')
<script>
function doDel(id){
	$.confirm({
		content:"您确定要删除该文件吗？",
		confirm:function(){
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.post("{{ route('/admin/resource/del')}}",{id:id},function(data){
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
function doClassifyDel(cid){
	$.confirm({
		content:"您确定要删除该分类吗？分类下文件将移入默认分类",
		confirm:function(){
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.post("{{ route('/admin/resource/classify/del')}}",{cid:cid},function(data){
				if(data.code == '1'){
					$.alert({
						content:data.data,
						cofirmButtonClass:"btn-success",
						confirm:function(){
							window.location.href = "{{ route('/admin/resource') }}";
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