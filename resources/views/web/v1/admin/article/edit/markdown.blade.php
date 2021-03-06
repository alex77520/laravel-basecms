@extends('web.v1.base.admin')
@section('title', '普通模式')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/web/plugins/editor.md-master') }}/css/editormd.min.css">
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
            <form action="{{ route('/admin/article/edit/markdown') }}" name="" method="post" id="publish-form">
                {{ csrf_field() }}
                <input type="hidden" name="post_id" value="{{$post->post_id}}">
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label for="Title">分类<span class="text-danger">*</span></label>
                            <select class="form-control" data-plugin="select2" name="classify[]" multiple>
                            @if ($classifys != null)
                                @foreach ($classifys as $classify)
                                    @if (in_array($classify->pc_id,$classifyIds))
                                        <option value="{{ $classify->pc_id }}" selected="selected">{{ $classify->name }}</option>
                                    @else
                                        <option value="{{ $classify->pc_id }}">{{ $classify->name }}</option>
                                    @endif
                                    @if (count($classify->children) > 0)
                                        @foreach ($classify->children as $son)
                                            @if (in_array($son->pc_id,$classifyIds))
                                                <option value="{{ $son->pc_id }}" selected="selected">&nbsp;&nbsp;&nbsp;|--{{ $son->name }}</option>
                                            @else
                                                <option value="{{ $son->pc_id }}">&nbsp;&nbsp;&nbsp;|--{{ $son->name }}</option>
                                            @endif
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
                            <input type="text" class="form-control" placeholder="标题" name="title" value="{{$post->title}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="source">来源<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="source" placeholder="信息来源" name="source" value="{{$post->source}}">
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="tags">标签</label>
                            <input type="text" class="form-control" id="tags" placeholder="多个标签用半角逗号(,)隔开" name="tags" value="{{$post->tags}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    {{-- <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="form-group">
                            @if ($post->interval == '0')
                                <label for="interval">定时发布 <a href="javascript:clearIntervalTime();" id="clearIntervalTime" style="font-size:8px;display:none;">清除定时</a></label>
                                <input type="text" id="intervalTime" class="form-control" data-plugin="datetimepicker" placeholder="为空则指即时发布" name="interval">
                            @else
                                <label for="interval">定时发布 <a href="javascript:clearIntervalTime();" id="clearIntervalTime" style="font-size:8px;display:block;">清除定时</a></label>
                                <input type="text" id="intervalTime" class="form-control" data-plugin="datetimepicker" placeholder="为空则指即时发布" name="interval" value="{{date('Y-m-d H:i:s',$post->interval)}}">
                            @endif
                        </div>
                    </div> --}}
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="show">是否可见<span class="text-danger">*</span></label>
                            <select name="show" id="show" class="form-control" data-plugin="select2">
                                <option value="1" @if($post->show == '1') selected="selected" @endif>显示</option>
                                <option value="2" @if($post->show != '1') selected="selected" @endif>隐藏</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <input type="hidden" value="{{$post->cover}}" name="imgs" />
                            <label for="exampleInputFile">选择图片</label>
                            <br />
                            <div class="img-list">
                                @if(isset($post->images))
                                    @foreach($post->images as $image)
                                        <div class="avatar avatar-xl">
                                            <img src="{{$image}}" alt="">
                                        </div>
                                    @endforeach
                                @endif
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
                        <div id="editormd">
                            <textarea name="content" style="display:none;" rows="15">{{$post->Content->content}}</textarea>
                        </div>
                        <a href="{{ route('/admin/resource/dialog',['method'=>'img','id'=>'editorMdChooseImg']) }}" data-toggle="modal" data-function="FillEditorMd" data-target="#commonModal" id="editorMdChooseImg" style="display:none;">选择图片</a>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                            <button type="submit" class="btn btn-primary btn-md">保存</button>
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
<script type="text/javascript" src="{{ URL::asset('/web/plugins/editor.md-master') }}/editormd.js"></script>
<script type="text/javascript" src="{{ URL::asset('/web/plugins/editor.md-master') }}/languages/zh-cn.js"></script>
<script>
    var theme = true; // 定义默认是light的主题
    var editor = editormd("editormd", {
        height: 640,
        path : "{{ URL::asset('/web/plugins/editor.md-master/lib') }}/",
        editorTheme : 'base16-light',
        toolbarIcons : function() {
            // Or return editormd.toolbarModes[name]; // full, simple, mini
            // Using "||" set icons align right.
            return [
               "undo", "redo", "|", 
                "bold", "del", "italic", "quote", "ucwords", "uppercase", "lowercase", "|", 
                "h1", "h2", "h3", "h4", "h5", "h6", "|", 
                "list-ul", "list-ol", "hr", "|",
                "link", "reference-link","chooseImg", "code", "preformatted-text", "code-block", "table", "datetime", "emoji", "html-entities", "pagebreak", "|",
                "goto-line", "watch", "preview", "clear", "search", "|",
                "help", "info", "changeModel"
            ]
        },
        toolbarIconTexts : {
            changeModel : '更换主题'
        },
        toolbarIconsClass : {
            chooseImg : "fa-picture-o",  // 指定一个FontAawsome的图标类
        },
        // 自定义工具栏按钮的事件处理
        toolbarHandlers : {
            /**
                * @param {Object}      cm         CodeMirror对象
                * @param {Object}      icon       图标按钮jQuery元素对象
                * @param {Object}      cursor     CodeMirror的光标对象，可获取光标所在行和位置
                * @param {String}      selection  编辑器选中的文本
                */
            chooseImg : function(cm, icon, cursor, selection) {
                $('#editorMdChooseImg').click();
                // 替换选中文本，如果没有选中文本，则直接插入
                //cm.replaceSelection("[" + selection + ":testIcon]");
                // 如果当前没有选中的文本，将光标移到要输入的位置
                if(selection === "") {
                    cm.setCursor(cursor.line, cursor.ch + 1);
                }
            },
            changeModel: function(cm, icon, cursor, selection) {
                changeModel();
            }
        },
        
        lang : {
            toolbar : {
                chooseImg : "选择图片",  // 自定义按钮的提示文本，即title属性
                changeModel: "更换样式"
            }
        },
    });

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
                        Toast('保存成功');
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
    $("input[name='imgs']").val(ids);
    $('.img-list').html(str);
}
function FillEditorMd(data){
    var json = data.list;
    $.each(json,function(){
        var str = '!['+this.name+']('+this.url+' "'+this.name+'")\n';
        editor.insertValue(str);
    })
}
function changeModel(){
    if(theme == true){
        editor.setEditorTheme('base16-dark');
        theme = false;
    }else{
        editor.setEditorTheme('base16-light');
        theme = true;
    }
}
</script>
@stop