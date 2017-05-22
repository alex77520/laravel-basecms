@extends('web.v1.base.admin')
@section('title', '新闻管理')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@inject('controller', 'App\Http\Controllers\web\Controller')
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
						<hr class="m-0 m-b-md" style="border-color: #ddd;">
						<!-- mail label list -->
						<div class="list-group">
							<h4>筛选</h4>
                            <hr />
                            <form action="{{route('/admin/posts')}}" method="get">
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
                                    <label for="group">所属者</label>
                                    <select name="group" id="group" class="form-control" data-plugin="select2">
                                        <option value="">不限</option>
                                        @if ($groups != null)
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->gid }}" @if(isset($search['group'])) @if($search['group'] == $group->gid) selected="selected" @endif @endif>{{ $group->name }}</option>
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
                                    <label for="status">状态</label>
                                    <select name="status" id="status" class="form-control" data-plugin="select2">
                                        <option value="0" @if(isset($search['status'])) @if($search['status'] == '0') selected="selected" @endif @endif>不限</option>
                                        <option value="1" @if(isset($search['status'])) @if($search['status'] == '1') selected="selected" @endif @endif>正常</option>
                                        <option value="2" @if(isset($search['status'])) @if($search['status'] == '2') selected="selected" @endif @endif>屏蔽</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="type">文章类型</label>
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
							@if ($controller->HasLimit('PostBans') !== false)
								<a href="javascript:unban();" class="btn btn-success">通过审核</a>
								<a href="javascript:ban();" class="btn btn-danger">屏蔽</a>
							@endif
							</div>
							<div class="btn-group" role="group">
                                @if ($controller->HasLimit('PostDisables') !== false)
                                    <a href="javascript:enable();" class="btn btn-default"><i class="mdi mdi-eye"></i> 启用</a>
                                    <a href="javascript:disable();" class="btn btn-default"><i class="mdi mdi-eye-off"></i> 隐藏</a>
                                @endif
							</div>
							@if ($controller->HasLimit('PostRestores') !== false)
                                <a href="javascript:restore();" class="btn btn-primary"><i class="mdi mdi-delete"></i> 恢复</a>
                            @endif
							@if ($controller->HasLimit('PostDels') !== false)
                                <a href="javascript:del();" class="btn btn-danger"><i class="mdi mdi-delete"></i> 删除</a>
                            @endif
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
                                        <th>显示/状态</th>
                                        <th>删除</th>
                                        <th>类型</th>
                                        <th>置顶</th>
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
                                    <td><a href="{{ route('/admin/posts/intro',['post_id'=>$post->post_id]) }}" data-toggle="modal" data-target="#commonModal-lg" >{{ $post->title }}</a></td>
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
                                        /
                                        @if ( $post->status == '1' )
                                            <span class="text-success">通过</span>
                                        @else
                                            <span class="text-warning">待审核</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ( $post->deleted_at == null )
                                            <span class="text-success">正常</span>
                                        @else
                                            <span class="text-danger">已删除</span>
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
                                    <td>{{ $post->created_at }}</td>
                                    <td>
                                        <div class="btn-group dropup">
                                            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-hc-lg mdi-settings"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ route('/admin/posts/intro',['post_id'=>$post->post_id]) }}" data-toggle="modal" data-target="#commonModal-lg" >详细信息</a></li>
                                                @if ($controller->HasLimit('PostDisable') !== false)
                                                <li>
                                                    @if($post->show == '1')
                                                        <a href="javascript:doDisable('{{$post->post_id}}')" class="text-danger">设为隐藏</a>
                                                    @else 
                                                        <a href="javascript:doEnable('{{$post->post_id}}')" class="text-success">设为显示</a>
                                                    @endif
                                                </li>
                                                @endif
                                                @if ($controller->HasLimit('PostBan') !== false)
                                                <li>
                                                    @if($post->status == '1')
                                                        <a href="javascript:doBan('{{$post->post_id}}')" class="text-warning">屏蔽</a>
                                                    @else 
                                                        <a href="javascript:doUnban('{{$post->post_id}}')" class="text-info">通过审核</a>
                                                    @endif
                                                </li>
                                                @endif
                                                @if ($controller->HasLimit('PostRestore') !== false)
                                                <li>
                                                    <a href="javascript:doRestore('{{$post->post_id}}')" class="text-danger">恢复</a>
                                                </li>
                                                @endif
                                                @if ($controller->HasLimit('PostDel') !== false)
                                                <li>
                                                    <a href="javascript:doDel('{{$post->post_id}}')" class="text-danger">永久删除</a>
                                                </li>
                                                @endif
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
function doRestore(id){
    $.confirm({
        content:"您确定要恢复该文章吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/posts/restore')}}",{post_id:id},function(data){
                if(data.code == '1'){
                    $.alert({
                        content:'成功恢复 '+data.data+' 条记录',
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
function doDel(id){
    $.confirm({
        content:"您确定要删除该文章？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/posts/delete')}}",{post_id:id},function(data){
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
function doEnable(id){
    $.confirm({
        content:"您确定要将该文章“显示”吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/posts/enable')}}",{post_id:id},function(data){
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
        content:"您确定要将该文章“隐藏”吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/posts/disable')}}",{post_id:id},function(data){
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
function doUnban(id){
    $.confirm({
        content:"您确定要将该文章“通过审核”吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/posts/unban')}}",{post_id:id},function(data){
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
function doBan(id){
    $.confirm({
        content:"您确定要将该文章“屏蔽”吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/posts/ban')}}",{post_id:id},function(data){
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
function restore(){
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
        content:"你确定要恢复这 "+cnt+" 条数据吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/posts/restores')}}",{ids:ids},function(data){
                if(data.code == '1'){
                    $.alert({
                        content:'成功恢复 '+data.data+' 条记录',
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
            $.post("{{ route('/admin/posts/deletes')}}",{ids:ids},function(data){
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
            $.post("{{ route('/admin/posts/enables')}}",{ids:ids},function(data){
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
            $.post("{{ route('/admin/posts/disables')}}",{ids:ids},function(data){
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
function unban(){
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
        content:"你确定要将这 "+cnt+" 条数据“通过审核”吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/posts/unbans')}}",{ids:ids},function(data){
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
function ban(){
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
        content:"你确定要将这 "+cnt+" 条数据“屏蔽”吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/admin/posts/bans')}}",{ids:ids},function(data){
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