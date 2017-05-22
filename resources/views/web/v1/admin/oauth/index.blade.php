@extends('web.v1.base.admin')
@section('title', '客户端列表')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('container')
    @parent
    @inject('controller', 'App\Http\Controllers\Web\v1\Controller')
	<section class="app-content">
        <div class="row">
			<div class="col-md-3 col-sm-6">
                <a href="{{ route('/oauth/add') }}" data-toggle="modal" data-target="#commonModal" class="btn btn-success">添加客户端</a>
			</div>
		</div>
		<hr />
        <div class="row">
			<div class="col-md-12">
				<div class="widget p-lg">
					<h4 class="m-b-lg">客户端列表</h4>
					<p class="m-b-lg docs">
						
					</p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>名称</th>
								<th>客户端ID</th>
								<th>回调URL</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
            				@foreach ( $clients as $client )
                            <tr>
                                <td>{{ $client->client_name }}</td>
                                <td>{{ $client->client_id }}</td>
                                <td>{{ $client->redirect_uri }}</td>
                                <td>
                                <a href="{{ route('/oauth/token',['id'=>$client->client_id] ) }}" ><i class="fa fa-group"></i></a>
                                <a href="{{ route('/oauth/encrypt',['id'=>$client->client_id] ) }}" data-toggle="modal" data-target="#commonModal" title="密钥"><i class="fa fa-search"></i></a>
                                <a href="javascript:doDel('{{ $client->client_id }}');"  title="删除"><i class="mdi mdi-delete text-danger"></i></a>
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
function doDel(id){
    $.confirm({
        content:"您确定要删除选中的APP吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/oauth/del')}}",{id:id},function(data){
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