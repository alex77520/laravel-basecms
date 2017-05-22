@extends('web.v1.base.admin')
@section('title', '轮播图/友情链接管理')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    tr>td,tr>th{
        text-align:center;
        line-height:inherit;
    }
</style>
@stop
@section('container')
    @parent
    @inject('controller', 'App\Http\Controllers\web\v1\Controller')
    <section class="app-content">
    @if ($controller->HasLimit('SliderEdit') !== false)
        <div class="row">
			<div class="col-md-3 col-sm-6">
				<a href="{{ route('/slider/item/add',['cid'=>$cid]) }}" class="btn btn-success">新增条目</a>
			</div>
		</div>
		<hr />
    @endif
        <div class="widget">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>图片</th>
                        <th>名称</th>
                        <th>URI</th>
                        <th>有效期</th>
                        @if ($controller->HasLimit('SliderEdit') !== false || $controller->HasLimit('SliderDel') !== false)
                        <th>操作</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @foreach( $sliders as $slider)
                    <tr>
                        <td>
                            @if ( $slider->fid != null)
                            <div class="avatar avatar-sm avatar-circle">
                                <a href="{{ $controller->getImgPath($slider->Files->uuid) }}" data-lightbox="gallery-2" data-title="{{ $slider->Files->name }}">
                                    <img src="{{ $controller->getImgPath($slider->Files->uuid) }}" class="img-responsive">
                                </a>
                            </div>
                            @else 
                            无图
                            @endif
                        </td>
                        <td>{{ $slider->name }}</td>
                        <td>{{ $slider->uri }}</td>
                        @if ($slider->up_time == 0 && $slider->down_time == 0)
                        <td>永久</td>
                        @else 
                        <td>{{ date('Y-m-d H:i',$slider->up_time) }} 至 {{ date('Y-m-d H:i',$slider->down_time) }}</td>
                        @endif
                        <td>
                        @if ($controller->HasLimit('SliderEdit') !== false)
                        <a href="{{ route('/slider/item/edit',['id'=>$slider->id]) }}">修改</a>
                        @endif
                        @if ($controller->HasLimit('SliderDel') !== false)
                        <a href="javascript:doDel('{{ $slider->id }}')">删除</a>
                        @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@stop

@section('js')
<script>
function doDel(id){
		$.confirm({
			content:"您确定要删除该条目吗？",
			confirm:function(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.post("{{ route('/slider/item/doDel')}}",{id:id},function(data){
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