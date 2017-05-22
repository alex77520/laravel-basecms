@extends('web.v1.base.admin')
@section('title', '通知信息')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@inject('controller', 'App\Http\Controllers\web\Controller')
@section('container')
    @parent
	<section class="app-content">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<div class="m-b-lg">
                            <a href="javascript:read();" class="btn btn-primary"> 标记为已读</a>
                            <a href="javascript:del();" class="btn btn-danger"><i class="mdi mdi-delete"></i> 删除</a>
						</div>
					</div>
				</div>

				<div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <h4 class="m-b-lg">通知</h4>
                            <p class="m-b-lg docs">
                            </p>
                            <div class="list-group">
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="row">
                                            <th class="col-md-1"><input type="checkbox" id="checkAll"></th>
                                            <th class="col-md-9">标题</th>
                                            <th class="col-md-2">发布时间</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($msgs as $msg)
                                            @if($msg->is_visit == '1')
                                            <tr class="row active">
                                            @else
                                            <tr class="row">
                                            @endif
                                                <td class="col-md-1"><input name="ids[]" value="{{$msg->id}}" type="checkbox"/></td>
                                                <td class="col-md-9">
                                                @if($msg->Notice->to == '1')
                                                    <span class="text-success">[站点]</span>
                                                @else
                                                    <span class="text-success">[小组]</span>
                                                @endif
                                                @if($msg->Notice->level == '2')
                                                    <span class="text-primary">[重要]</span>
                                                @elseif($msg->Notice->level == '3')
                                                    <span class="text-danger">[紧急]</span>
                                                @else
                                                    <span class="text-dark">普通</span>
                                                @endif
                                                @if($msg->is_visit == '1')
                                                    <a href="{{route('/admin/msg/intro',['id'=>$msg->id])}}" class="text-dark" data-toggle="modal" data-target="#commonModal">{{$msg->Notice->title}}</a>
                                                @else
                                                    <a href="{{route('/admin/msg/intro',['id'=>$msg->id])}}"  data-toggle="modal" data-target="#commonModal">{{$msg->Notice->title}}</a>
                                                @endif
                                                </td>
                                                <td class="col-md-2">{{$msg->created_at}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
							</div>
                            {{ $msgs->links() }}
                        </div><!-- .widget -->
                    </div>
				</div>
			</div><!-- END column -->
		</div><!-- .row -->
	</section>
@stop

@section('js')
<script>
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
        Toast('未选中数据');
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
            $.post("{{ route('/admin/msg/deletes')}}",{ids:ids},function(data){
                if(data.code == '1'){
                    Toast('成功删除 '+data.data+' 条记录');
                    setTimeout(function(){
                        window.location.reload();
                    },1500);
                }else{
                    $.alert(data.data);
                }
            },'json');
        }
    })
}
function read(){
    var cnt = 0;
    var ids = "";
    $("input[name='ids[]']").each(function(){
        if($(this).is(':checked')){
            ids += $(this).val()+",";
            cnt++;
        }
    });
    if(cnt <= 0){
        Toast('未选中数据');
        return;
    }
    //提交
    $.confirm({
        content:"你确定要标记这 "+cnt+" 条数据为“已读”吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/msg/reads')}}",{ids:ids},function(data){
                if(data.code == '1'){
                    Toast('成功标记 '+data.data+' 条记录');
                    setTimeout(function(){
                        window.location.reload();
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