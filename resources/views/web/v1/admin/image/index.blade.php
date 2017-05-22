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
							<a href="#" class="btn btn-default">上传图片</a>
						</div>
					</div>
				</div>
		</div>

		<!-- Image Gallery -->
		<div class="gallery row">
            @foreach ( $files as $file)
				<div class="col-xs-6 col-sm-4 col-md-3">
					<div class="gallery-item">
						<div class="thumb">
							<a href="{{ $controller->getImgPath($file->uuid) }}" data-lightbox="gallery-2" data-title="{{ $file->name }}">
								<img class="img-responsive" src="{{ $controller->getImgPath($file->uuid) }}" alt=""style="height:200px;" >
							</a>
						</div>
						<div class="caption">
                        {{ $file->name }}
                            @if ($controller->HasLimit('ImageDel'))
                            <a href="javascript:doDel('{{ $file->fid }}');" class="pull-right" title="删除">
                                <i class="mdi mdi-delete text-danger"></i>
                            </a>
                            @endif
                        </div>
					</div>
				</div>
            @endforeach
		</div><!-- END .gallery -->
        {{ $files->links() }}
	</section><!-- #dash-content -->

@stop

@section('js')
<script>
function doDel(id){
		$.confirm({
			content:"您确定要删除该图片？",
			confirm:function(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.post("{{ route('/image/doDel')}}",{id:id},function(data){
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