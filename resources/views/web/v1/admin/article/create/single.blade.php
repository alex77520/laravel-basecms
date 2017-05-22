@extends('web.v1.base.admin')
@section('title', '普通模式')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/web/plugins/wangEditor') }}/css/wangEditor.min.css">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/web/v1/libs/bower/eonasdan-bootstrap-datetimepicker/build') }}/css/bootstrap-datetimepicker.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('container')
@parent
<section class="app-content">
    <div class="row">
        <div class="col-md-12">
            <div class="mail-toolbar m-b-lg">
                <h3>普通模式</h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('/admin/article/create/single') }}" name="" method="post" id="publish-form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label for="Title">分类<span class="text-danger">*</span></label>
                            <select class="form-control" data-plugin="select2" name="classify[]" multiple>
                             @if ($classifys != null)
                                @foreach ($classifys as $classify)
                                    <option value="{{ $classify->pc_id }}">{{ $classify->name }}</option>
                                    @if (count($classify->children) > 0)
                                        @foreach ($classify->children as $son)
                                            <option value="{{ $son->pc_id }}">&nbsp;&nbsp;&nbsp;|--{{ $son->name }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                             @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label>标题<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="标题" name="title">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="source">来源<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="source" placeholder="信息来源" name="source">
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="tags">标签</label>
                            <input type="text" class="form-control" id="tags" placeholder="多个标签用半角逗号(,)隔开" name="tags">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="interval">定时发布 <a href="javascript:clearIntervalTime();" id="clearIntervalTime" style="font-size:8px;display:none;">清除定时</a></label>
                            <input type="text" id="intervalTime" class="form-control" data-plugin="datetimepicker" placeholder="为空则指即时发布" name="interval">
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="show">是否可见<span class="text-danger">*</span></label>
                            <select name="show" id="show" class="form-control" data-plugin="select2">
                                <option value="1">显示</option>
                                <option value="2" selected="selected">隐藏</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <input type="hidden" value="" name="imgs" />
                            <label for="exampleInputFile">选择图片</label>
                            <br />
                            <div class="img-list">
                            
                            </div>
                            <br />
                            <a href="{{ route('/admin/resource/dialog',['method'=>'img']) }}" data-toggle="modal" data-function="FillImg" data-target="#commonModal" class="btn btn-success btn-xs" id="alert">选择图片</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                        <label for="show">正文 <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" id="content" rows="15"></textarea>
                        <a href="{{ route('/admin/resource/dialog',['method'=>'img','id'=>'wangEditorChooseImg']) }}" data-toggle="modal" data-function="FillWangEditor" data-target="#commonModal" id="wangEditorChooseImg" style="display:none;">选择图片</a>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                            <button type="submit" class="btn btn-primary btn-md">创建</button>
                    </div>
                </div>
                <hr />
            </form>
        </div><!-- END column -->
    </div><!-- .row -->
</section>
@stop

@section('js')
<script type="text/javascript" src="{{ URL::asset('/web/v1/libs/bower/eonasdan-bootstrap-datetimepicker/build') }}/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('/web/plugins/wangEditor') }}/js/wangEditor.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('/web/plugins/wangEditor') }}/js/chooseImg.js"></script>
<script>
    var editor = new wangEditor('content');
    editor.config.menus = [
        'source',
        '|',     // '|' 是菜单组的分割线
        'bold',
        'underline',
        'italic',
        'strikethrough',
        'eraser',
        'forecolor',
        'bgcolor',
        '|',
        'chooseImg',
        'video',
        'location',
        'insertcode',
        '|',
        'quote',
        'fontfamily',
        'fontsize',
        'head',
        'unorderlist',
        'orderlist',
        'alignleft',
        'aligncenter',
        'alignright',
        '|',
        'undo',
        'redo',
        'fullscreen'
     ];
    editor.create();
        // 监控Textarea里的东西
    editor.onchange = function () {
        // 编辑区域内容变化时，实时打印出当前内容
            if(editor.$txt.formatText().length > 0){
                $('.btn-md').removeAttr('disabled');
            }
    };
    $('#intervalTime').datetimepicker({  
        format: 'YYYY-MM-DD HH:mm:ss'
    }); 
    $(function(){
        $('#publish-form').bootstrapValidator({
            message: '填写的数据不合法',
            feedbackIcons: {
                valid: 'fa fa-ok',
                invalid: 'fa fa-remove',
                validating: 'fa fa-refresh'
            },
            fields: {
                'classify[]': {
                    message: '分类不合法',
                    validators: {
                        notEmpty: {
                            message: '分类不能为空'
                        },
                    }
                },
                'title': {
                    message: '标题不合法',
                    validators: {
                        notEmpty: {
                            message: '标题不能为空'
                        },
                        stringLength: {
                            min: 2,
                            max: 50,
                            message: '标题长度为2-50位',
                        },
                    }
                },
                source: {
                    message: '信息来源字段不合法',
                    validators: {
                        notEmpty: {
                            message: '信息来源不能为空'
                        },
                        stringLength: {
                            min: 2,
                            max: 20,
                            message: '信息来源长度为2-10位',
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
                            message: '内容不能为空',
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
                        $.confirm({
                            content:data.data+"<br />是否继续创建？",
                            cofirmButtonClass:"btn-success",
                            confirm:function(){
                                window.location.reload();
                            },
                            cancel:function(){
                                window.location.href="{{ route('/admin/article') }}";
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
        // 监听定时时间的设定Input
        $('#intervalTime').on('blur',function(){
            if($('#intervalTime').val() != ''){
                $('#clearIntervalTime').show();
            }else{
                $('#clearIntervalTime').hide();
            }
        });
    });
// 清除定时发布的时间
function clearIntervalTime(){
    $('#intervalTime').val('');
    $('#clearIntervalTime').hide();
}
function FillImg(data){
    var json = data.list;
    var str = "";
    var ids = "";
    for(var i = 0;i < json.length;i++){
        str += '<div class="avatar avatar-xl">'+
            '<img src="'+json[i].url+'" alt="">'+
            '</div>';
        ids += json[i].id + ',';
    }
    $("input[name='img']").val(ids);
    $('.img-list').html(str);
}
function FillWangEditor(data){
    var json = data.list;
    $.each(json,function(){
        //var str = '<p><br></p><p><img src="'+this.url+'" title="'+this.name+'" /><span style="display:block;text-align:center;">'+this.name+'</span></p>';
        var str = '<img src="'+this.url+'" title="'+this.name+'" />';
        editor.$txt.append(str);
    })
}
</script>
@stop