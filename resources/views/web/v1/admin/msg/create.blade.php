@extends('web.v1.base.admin')
@section('title', '组内信管理')
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
                        <div class="list-group">
							<a href="{{route('/admin/organize/notice/create')}}" class="text-color list-group-item"><i class="m-r-sm mdi mdi-account-multiple"></i>整站群发</a>
						</div><!-- .list-group --> 
                        <div class="list-group">
							<a href="{{route('/admin/organize/notice/create',['type'=>'user'])}}" class="text-color list-group-item"><i class="m-r-sm mdi mdi-account"></i>面向用户</a>
						</div><!-- .list-group -->
						<!-- mail label list -->
					<div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; right: 3px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div><!-- .app-actions-list -->
				</div><!-- .app-action-panel -->
			</div><!-- END column -->

			<div class="col-md-10">
				<div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <h4 class="m-b-lg">
                                @if(isset($type))
                                    选择用户
                                @else
                                    整组群发
                                @endif
                            </h4>
                                @if(isset($type))
                                    @if($type == 'user')
                                        <form action="{{ route('/admin/organize/notice/create/user')}}" method="post" id="edit-form">
                                        <div class="form-group">
                                            <label for="user">通知范围：</label>
                                            <select name="user[]" id="user" class="form-control" data-plugin="select2" multiple data-options="">
                                            <optgroup label="{{$group->name}}">
                                                <option value="{{$group->User->uid}}">{{$group->User->nickname}}[{{$group->User->account}}]</option>
                                                @if ($group->GroupUsers->count() > 0)
                                                    @foreach ($group->GroupUsers as $groupUser)
                                                        <option value="{{$groupUser->User->uid}}">{{$groupUser->User->nickname}}[{{$groupUser->User->account}}]</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                            </select>
                                        </div>
                                    @endif
                                @else
                                    <form action="{{ route('/admin/organize/notice/create')}}" method="post" id="edit-form">
                                @endif
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="type">消息等级：</label>
                                    <select name="type" id="type" class="form-control" data-plugin="select2">
                                        <option value="1">一般</option>
                                        <option value="2">重要</option>
                                        <option value="3">紧急</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="title">标题：</label>
                                    <input type="text" id="title" name="title" placeholder="限100字" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="content">正文：</label>
                                    <textarea name="content" id="content" cols="30" rows="10" class="form-control" placeholder="限200字"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-md">推送</button>
                            </form>
                        </div><!-- .widget -->
                    </div>
				</div>
			</div><!-- END column -->
		</div><!-- .row -->
	</section>
@stop

@section('js')
<script>
    $(function(){
        $('#edit-form').bootstrapValidator({
            message: '填写的数据不合法',
            feedbackIcons: {
                valid: 'fa fa-ok',
                invalid: 'fa fa-remove',
                validating: 'fa fa-refresh'
            },
            fields: {
                title: {
                    message: '标题不合法',
                    validators: {
                        notEmpty: {
                            message: '标题不能为空'
                        },
                        stringLength: {
                            min: 1,
                            max: 100,
                            message: '标题长度为1-100字',
                        },
                    }
                },
                content: {
                    message: '内容不合法',
                    validators: {
                        notEmpty: {
                            message: '内容不能为空'
                        },
                        stringLength: {
                            min: 1,
                            max: 100,
                            message: '内容长度为1-200字',
                        },
                    }
                },
                type: {
                    message: '等级不合法',
                    validators: {
                        notEmpty: {
                            message: '等级不为空'
                        },
                    }
                },
            }
        }).on('success.form.bv', function(e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            $.ajax({
                url: $form.attr('action'),
                type: "post",
                dataType: "json",
                data: $form.serialize(),
                success: function (data) {
                    if(data.code == '1'){
                        $.alert({
                            content:data.data,
                            cofirmButtonClass:"btn-success",
                            confirm:function(){
                                window.location.href="{{route('/admin/organize/notice')}}";
                            }
                        });
                    }else{
                        $.alert(data.data);
                    }
                },
                error: function (data) {
                    var errors = $.parseJSON(data.responseText);
                    $.each(errors, function (key, value) {
                        $("small[data-bv-for='"+key+"']").parent().addClass('has-error');
                        $("small[data-bv-for='"+key+"'][data-bv-validator='notEmpty']").html(value);
                        $("small[data-bv-for='"+key+"'][data-bv-validator='notEmpty']").show();
                    });
                }
            });
        });
    });
</script>
@stop