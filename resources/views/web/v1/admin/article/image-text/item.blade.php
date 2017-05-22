@extends('web.v1.base.admin')
@section('title', '图文模式 - 条目')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('container')
@parent
<section class="app-content">
    <div class="row">
        <div class="col-md-4">
            <div class="widget">
                <header class="widget-header">
                    <h4 class="widget-title">添加条目</h4>
                </header><!-- .widget-header -->
                <hr class="widget-separator">
                <div class="widget-body">
                    <form action="{{ route('/admin/article/image-text/item/add') }}" method="post" id="item-form">
                        {{ csrf_field() }}
                        <input type="hidden" name="post_id" value="{{ $post->post_id }}" />
                        <div class="form-group">
                            <label for="exampleInputEmail1">内容 *</label>
                            <textarea name="content" class="form-control"  rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">排序</label>
                            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="数字越小排序越前,默认1" name="sort">
                        </div>
                        <div class="form-group">
                            <label for="show">是否可见<span class="text-danger">*</span></label>
                            <select name="show" id="show" class="form-control">
                                <option value="1">显示</option>
                                <option value="2" selected="selected">隐藏</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="hidden" value="" name="image" />
                            <label for="exampleInputFile">选择图片</label> 
                            <br />
                            <div class="img-list">
                                
                            </div>
                            <br />
                            <a href="{{ route('/admin/resource/dialog',['method'=>'img']) }}" data-toggle="modal" data-function="FillImg" data-target="#commonModal" class="btn btn-success btn-xs" id="alert">选择图片</a>
                        </div>
                        <button type="submit" class="btn btn-primary btn-md btn-add-item">确认</button>
                    </form>
                </div><!-- .widget-body -->
            </div><!-- .widget -->
        </div>
        <div class="col-md-8">
            @foreach($items as $item)
                <div class="mail-item">
                    <table class="mail-container">
                        <tr>
                            <td class="mail-left">
                                <div class="avatar avatar-lg">
                                    <a href="{{ $item->picture }}" data-lightbox="gallery-2">
                                        <img class="img-responsive" src="{{ $item->picture }}" >
                                    </a>
                                </div>
                            </td>
                            <td class="mail-center">
                                <div class="mail-item-header">
                                    <h4 class="mail-item-title"><a href="javascript:void(0);" class="title-color">{{ $item->content }}</a></h4>
                                    {{-- <a href="#"><span class="label label-success">client</span></a>
                                    <a href="#"><span class="label label-primary">work</span></a> --}}
                                </div>
                                {{-- <p class="mail-item-excerpt">{{ $item->content }}</p> --}}
                            </td>
                            <td class="mail-right">
                                @if ( $item->show == '1')
                                    <p><span class="label label-success">显示</span>&nbsp;<span class="label label-info">{{ $item->sort }}</span></p>
                                @else 
                                    <p><span class="label label-warning">隐藏</span>&nbsp;<span class="label label-info">{{ $item->sort }}</span></p>
                                @endif
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="mdi mdi-hc-lg mdi-settings"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:doItemEdit('{{ $item->pc_id }}');" >修改</a></li>
                                        <li><a href="javascript:doItemRemove('{{ $item->pc_id }}');" class="text-danger">删除</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            @endforeach
        </div><!-- END column -->
    </div><!-- .row -->
</section>
@stop

@section('js')
<script>
    $(document).ready(function(){
        $('#item-form').bootstrapValidator({
            message: '填写的数据不合法',
            feedbackIcons: {
                valid: 'fa fa-ok',
                invalid: 'fa fa-remove',
                validating: 'fa fa-refresh'
            },
            fields: {
                content: {
                    message: '内容不合法',
                    validators: {
                        notEmpty: {
                            message: '内容不为空'
                        },
                    }
                },
                /*
                image: {
                    message: '请选择图片',
                    validators: {
                        notEmpty: {
                            message: '请选择图片'
                        },
                    }
                },
                */
                sort: {
                    message: '排序不合法',
                    validators: {
                        notEmpty: {
                            message: '排序不为空'
                        },
                    }
                }
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
                        setTimeout(function(){
                            window.location.reload();
                        },1000);
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
    // 填充图片
    function FillImg(data){
        var json = data.list;
        var str = "";
        var ids = "";
        if(json.length > 1){
            $.alert('仅可选择一张图片，请重新选择');
            return;
        }
        str = '<div class="avatar avatar-xl">'+
            '<img src="'+json[0].url+'" alt="">'+
            '</div>';
        ids = json[0].id;
        $("input[name='image']").val(ids);
        $('.img-list').html(str);
    }
    // 填充表单
    function FillForm(data){
        clearForm();
        $('textarea[name="content"]').text(data.content);
        $('input[name="sort"]').val(data.sort);
        if(data.image != null){
            $('input[name="image"]').val(data.picture);
            $('.img-list').html('<div class="avatar avatar-xl">'+
                '<img src="'+data.image+'" alt="">'+
                '</div>');
        }
        $('#show').val(data.show);
        console.log($('#show').val());
        $(".widget-title").html('修改条目<small class="pull-right"><a href="javascript:clearForm();">添加条目</a></small>');
        $(".btn-add-item").html("保存");
        $("#item-form").prepend('<input type="hidden" name="pc_id" value="'+data.pc_id+'" />');
        $("#item-form").attr('action',"{{ route('/admin/article/image-text/item/edit') }}"); //将提交地址修改
    }
    // 清除Form
    function clearForm(){
        $('textarea[name="content"]').html('');
        $('input[name="sort"]').val('');
        $('#show').val('2');
        $('.img-list').html('');
        $(".widget-title").html('添加条目');
        $(".btn-add-item").removeAttr("disabled");
        $(".btn-add-item").html("添加");
        $("#item-form input[name='pc_id']").remove(); //移除ID
        $("#item-form").attr('action',"{{ route('/admin/article/image-text/item/add') }}"); //地址修改回添加的Action
    }
    // 删除条目
    function doItemRemove(id){
        $.confirm({
			content:'您确定要删除该条目吗?',
			confirm:function(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
                $.post("{{ route('/admin/article/image-text/item/del') }}",{post_id:'{{$post->post_id}}',pc_id:id},function(msg){
                    if(msg.code == '1'){
                        Toast(msg.data);
                        setTimeout(function(){
                            window.location.reload();
                        },1500);
                    }else{
                        $.alert(msg.data);
                    }
                },'json');
			}
		})
    }
    // 修改条目
    function doItemEdit(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post("{{ route('/admin/article/image-text/item/get') }}",{post_id:'{{$post->post_id}}',pc_id:id},function(msg){
            FillForm(msg.data);
        },'json');
    }
</script>
@stop