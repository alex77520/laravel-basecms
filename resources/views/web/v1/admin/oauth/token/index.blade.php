@extends('web.v1.base.admin')
@section('title', '客户端列表')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('container')
    @parent
    @inject('controller', 'App\Http\Controllers\Web\v1\Controller')
	<section class="app-content">
		<hr />
        <div class="row">
			<div class="col-md-12">
				<div class="widget p-lg">
					<h4 class="m-b-lg">授权列表</h4>
					<p class="m-b-lg docs">
						
					</p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>昵称</th>
								<th>access_token</th>
								<th>refresh_token</th>
								<th>有效期(秒)</th>
								<th>授权时间</th>
								<th>最后登录时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
            				@foreach ( $lists as $list )
                            <tr>
                                <td>{{ $list->members->nickname }}</td>
                                <td>{{ $list->access_token }}</td>
                                <td>{{ $list->refresh_token }}</td>
                                <td>{{ $list->expire }}</td>
                                <td>{{ $list->created_at }}</td>
                                <td>{{ $list->updated_at }}</td>
                                <td>
                                <a href="javascript:doRemove('{{ $list->id }}');" >取消授权</a>
                                {{-- <a href="javascript:doOffline('{{ $list->id }}');">下线</a> --}}
                                </td>
                            </tr>
                            @endforeach
						</tbody>
					</table>
					</div>
			</div>
			
        </div><!-- .row -->
		
	</section>
@stop

@section('js')
<script>
function doRemove(id){
    $.confirm({
        content:"您确定要取消当前用户的授权吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/oauth/token/del')}}",{id:id},function(data){
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