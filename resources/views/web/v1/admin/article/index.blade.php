@extends('web.v1.base.admin')
@section('title', '新闻管理')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('container')
    @parent
	<section class="app-content">
		<div class="row">
			<div class="col-md-2">
				<div class="app-action-panel" id="inbox-action-panel">
					<div class="action-panel-toggle" data-toggle="class" data-target="#inbox-action-panel" data-class="open">
						<i class="fa fa-chevron-right"></i>
						<i class="fa fa-chevron-left"></i>
					</div><!-- .app-action-panel -->
					<div class="app-actions-list scrollable-container ps-container ps-theme-default" data-ps-id="ba4c682c-c426-d139-2fde-3d9a24ab539f">
						<!-- mail category list -->
						<div class="list-group">
							<a href="{{route('/admin/article/choose')}}" class="text-color list-group-item"><i class="m-r-sm mdi mdi-note-plus"></i>新增</a>
						</div><!-- .list-group -->
						<hr class="m-0 m-b-md" style="border-color: #ddd;">
						<!-- mail label list -->
						<div class="list-group">
							<h4>筛选</h4>
                            <hr />
                            <form action="{{route('/admin/article')}}" method="get">
                                <div class="form-group">
                                    <label for="classify">分类</label>
                                    <select name="classify" id="classify" class="form-control" data-plugin="select2">
                                        <option value="">不限</option>
                                        @if ($classifys != null)
                                            @foreach ($classifys as $classify)
                                                <option value="{{ $classify->pc_id }}" @if(isset($search['classify'])) @if($search['classify'] == $classify->pc_id) selected="selected" @endif @endif>{{ $classify->name }}</option>
                                                @if (count($classify->children) > 0)
                                                    @foreach ($classify->children as $son)
                                                        <option value="{{ $son->pc_id }}" @if(isset($search['classify'])) @if($search['classify'] == $son->pc_id) selected="selected" @endif @endif>&nbsp;&nbsp;&nbsp;|--{{ $son->name }}</option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="show">显示</label>
                                    <select name="show" id="show" class="form-control" data-plugin="select2">
                                        <option value="0" @if(isset($search['show'])) @if($search['show'] == '0') selected="selected" @endif @endif>不限</option>
                                        <option value="1" @if(isset($search['show'])) @if($search['show'] == '1') selected="selected" @endif @endif>显示</option>
                                        <option value="2" @if(isset($search['show'])) @if($search['show'] == '2') selected="selected" @endif @endif>隐藏</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="type">类型</label>
                                    <select name="type" id="type" class="form-control" data-plugin="select2">
                                        <option value="0" @if(isset($search['type'])) @if($search['type'] == '0') selected="selected" @endif @endif>不限</option>
                                        <option value="1" @if(isset($search['type'])) @if($search['type'] == '1') selected="selected" @endif @endif>普通</option>
                                        <option value="2" @if(isset($search['type'])) @if($search['type'] == '2') selected="selected" @endif @endif>图文</option>
                                        <option value="3" @if(isset($search['type'])) @if($search['type'] == '3') selected="selected" @endif @endif>Markdown</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="search">关键字</label>
                                     @if(isset($search['key'])) 
                                        <input type="text" name="key" placeholder="标题/来源/标签" class="form-control" value="{{$search['key']}}">
                                        @else
                                        <input type="text" name="key" placeholder="标题/来源/标签" class="form-control" value="">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block"><i class="mdi mdi-magnify"></i> 检索</button>
                                </div>
                            </form>
						</div><!-- .list-group -->
					<div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; right: 3px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div><!-- .app-actions-list -->
				</div><!-- .app-action-panel -->
			</div><!-- END column -->

			<div class="col-md-10">
				<div class="row">
					<div class="col-md-12">
						<div class="m-b-lg">
							<div class="btn-group" role="group">
								<a href="javascript:enable();" class="btn btn-default"><i class="mdi mdi-eye"></i> 启用</a>
								<a href="javascript:disable();" class="btn btn-default"><i class="mdi mdi-eye-off"></i> 隐藏</a>
							</div>
							<a href="javascript:del();" class="btn btn-danger"><i class="mdi mdi-delete"></i>批量删除</a>
						</div>
					</div>
				</div>

				<div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <h4 class="m-b-lg">文章</h4>
                            <p class="m-b-lg docs">
                            </p>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>标题</th>
                                        <th>信息来源</th>
                                        <th>分类</th>
                                        <th>显示</th>
                                        <th>类型</th>
                                        <th>置顶</th>
                                        <th>定时</th>
                                        <th>创建时间</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($posts as $post)
                                <tr>
                                    <td>
                                        <input type="checkbox" value="{{$post->post_id}}" name="ids[]">
                                    </td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->source }}</td>
                                    <td>
                                        @foreach($post->ClassifyRelation as $key=>$relation)
                                            @if($key == count($post->ClassifyRelation)-1)
                                                {{ $relation->classify->name }}
                                            @else
                                                {{ $relation->classify->name }}|
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ( $post->show == '1' )
                                            <span class="text-success">显示</span>
                                        @else
                                            <span class="text-warning">隐藏</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($post->single == '1')
                                            @if ($post->markdown =='1')
                                                <span class="text-info">Markdown</span>
                                            @else
                                                <span class="text-dark">普通</span>
                                            @endif
                                        @else
                                            <span class="text-primary">图文</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($post->top_time > 0)
                                            @if($post->top_type == '1')
                                                分类
                                            @elseif($post->top_type == '2')
                                                首页
                                            @elseif($post->top_type == '3')
                                                首页+分类
                                            @else
                                                无
                                            @endif
                                        @else
                                            无
                                        @endif
                                    </td>
                                    <td>
                                        @if ($post->interval == 0)
                                            否
                                        @else
                                        {{ date('m-d H:i',$post->interval) }}
                                        @endif
                                    </td>
                                    <td>{{ $post->created_at }}</td>
                                    <td>
                                        <div class="btn-group dropup">
                                            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-hc-lg mdi-settings"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ route('/admin/article/intro',['post_id'=>$post->post_id]) }}" data-toggle="modal" data-target="#commonModal" >详细信息</a></li>
                                                @if ($post->single != '1')
                                                <li><a href="{{ route('/admin/article/image-text/item',['post_id'=>$post->post_id]) }}">条目管理</a></li>
                                                @endif
                                                @if ($post->single == '1')
                                                    @if($post->markdown == '1')
                                                        <li><a href="{{ route('/admin/article/edit/markdown',['post_id'=>$post->post_id]) }}">修改</a></li>
                                                    @else
                                                        <li><a href="{{ route('/admin/article/edit/single',['post_id'=>$post->post_id]) }}">修改</a></li>
                                                    @endif
                                                @else
                                                    <li><a href="{{ route('/admin/article/edit/image-text',['post_id'=>$post->post_id]) }}">修改</a></li>
                                                @endif
                                                <li role="separator" class="divider"></li>
                                                <li>
                                                    @if($post->show == '1')
                                                        <a href="javascript:doDisable('{{$post->post_id}}')" class="text-warning">设为隐藏</a>
                                                    @else 
                                                        <a href="javascript:doEnable('{{$post->post_id}}')" class="text-success">设为显示</a>
                                                    @endif
                                                </li>
                                                <li>
                                                    <a href="javascript:doDel('{{$post->post_id}}')" class="text-danger">删除</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody></table>
                            {{ $posts->links() }}
                        </div><!-- .widget -->
                    </div>
				</div>
			</div><!-- END column -->
		</div><!-- .row -->
	</section>
@stop

@section('js')
<script>
function doDel(id){
    $.confirm({
        content:"您确定要删除该文章？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/article/delete')}}",{post_id:id},function(data){
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
function doEnable(id){
    $.confirm({
        content:"您确定要将文章状态置为“显示”吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/article/enable')}}",{post_id:id},function(data){
                if(data.code == '1'){
                    Toast('设置成功');
                    setTimeout(function(){
                        window.location.reload();
                    },1200);
                }else{
                    $.alert(data.data);
                }
            },'json');
        }
    })
}
function doDisable(id){
    $.confirm({
        content:"您确定要将文章状态置为“隐藏”吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/article/disable')}}",{post_id:id},function(data){
                if(data.code == '1'){
                    Toast('设置成功');
                    setTimeout(function(){
                        window.location.reload();
                    },1200);
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
            $.post("{{ route('/admin/article/deletes')}}",{ids:ids},function(data){
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
function enable(){
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
        content:"你确定要将这 "+cnt+" 条数据置为“显示”状态？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/article/enables')}}",{ids:ids},function(data){
                if(data.code == '1'){
                    Toast('操作成功，影响了 '+data.data+' 条数据');
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
function disable(){
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
        content:"你确定要将这 "+cnt+" 条数据置为“隐藏”状态？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/article/disables')}}",{ids:ids},function(data){
                if(data.code == '1'){
                    Toast('操作成功，影响了 '+data.data+' 条数据');
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