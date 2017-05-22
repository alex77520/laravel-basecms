@extends('web.v1.base.admin')
@section('title', '后台用户管理')
@section('container')
    @parent
    <section class="app-content">
        <div class="row">
            <!-- new row -->
            <div class="modal-header">
                <h4 class="modal-title">小组信息修改</h4>
            </div>
            <form action="{{ route('/admin/organize/edit') }}" method="post" id="edit-form">
                {{ csrf_field() }}
                <div class="modal-body">
                        <div class="form-group">
                            <label for="">小组ID：</label>
                            <input name="username" type="text" class="form-control" placeholder="用户名" disabled="disabled" value='{{ $group->gid }}'>
                        </div>
                        <div class="form-group">
                        <input type="hidden" value="" name="avatar" />
                        <label for="exampleInputFile">头像：</label>
                        <div class="img-list">
                            @if($group->avatar == null)
                            <div class="avatar avatar-xl avatar-circle">
                                <img src="{{ URL::asset('/web/v1/assets/images/221.jpg') }}" alt="">
                            </div>
                            @else 
                            <div class="avatar avatar-xl avatar-circle">
                                <img src="{{ $group->avatar }}" alt="">
                            </div>
                            @endif
                        </div>
                        <a href="{{ route('/admin/resource/dialog',['method'=>'img']) }}" data-toggle="modal" data-function="FillImg" data-target="#commonModal" class="btn btn-success btn-sm" id="alert">选择图片</a>
                    </div>
                        <div class="form-group">
                            <label for="">名称：</label>
                            <input name="name" type="text" class="form-control" placeholder="名称" value='{{ $group->name }}'>
                        </div>
                        <div class="form-group">
                            <label for="">简介：</label>
                            <textarea name="intro" id="" cols="30" rows="5" class="form-control" >{{ $group->intro }}</textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>  保存</button>
                </div>
            </form>
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
            name: {
                message: '名称不合法',
                validators: {
                    stringLength: {
                        min: 2,
                        max: 20,
                        message: '名称为2-20位',
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
                    Toast(data.data);
                    setTimeout(function(){
                        window.location.reload();
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
function FillImg(data){
    var json = data.list;
    if(json.length == 1){
        $('.img-list').html('<div class="avatar avatar-xl avatar-circle">'+
                '<img src="'+json[0].url+'" alt="">'+
                '</div>');
        $("input[name='avatar']").val(json[0].id);
    }else{
        Toast('仅可选择一张图片');
        return;
    }
}
</script>
@stop