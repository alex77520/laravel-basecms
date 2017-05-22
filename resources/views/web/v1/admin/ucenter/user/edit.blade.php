@extends('web.v1.base.admin')
@section('title', '后台用户管理')
@section('container')
    @parent
    <section class="app-content">
        <div class="row">
            <!-- new row -->
            <div class="modal-header">
                <h4 class="modal-title">个人资料修改</h4>
            </div>
            <form action="{{ route('/admin/ucenter/userinfo/edit') }}" method="post" id="edit-form">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" value="" name="avatar" />
                        <label for="exampleInputFile">头像：</label>
                        <div class="img-list">
                            @if($user->avatar == null)
                            <div class="avatar avatar-xl avatar-circle">
                                <img src="{{ URL::asset('/web/v1/assets/images/221.jpg') }}" alt="">
                            </div>
                            @else 
                            <div class="avatar avatar-xl avatar-circle">
                                <img src="{{ $user->avatar }}" alt="">
                            </div>
                            @endif
                        </div>
                        <a href="{{ route('/admin/resource/dialog',['method'=>'img']) }}" data-toggle="modal" data-function="FillImg" data-target="#commonModal" class="btn btn-success btn-sm" id="alert">选择图片</a>
                    </div>
                    <div class="form-group">
                        <label for="">用户名：</label>
                        <input type="text" class="form-control" placeholder="用户名" disabled="disabled" value='{{ $user->account }}'>
                    </div>
                    <div class="form-group">
                        <label for="">昵称：</label>
                        <input name="nickname" type="text" class="form-control" placeholder="昵称" value='{{ $user->nickname }}'>
                    </div>
                    <div class="form-group">
                        <label for="">邮箱：</label>
                        <input name="email" type="email" class="form-control" placeholder="邮箱账号" value='{{ $user->email }}'>
                    </div>
                    <div class="form-group">
                        <label for="">简介：</label>
                        <textarea name="intro" id="" cols="30" rows="5" class="form-control" >{{ $user->intro }}</textarea>
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
            nickname: {
                message: '昵称不合法',
                validators: {
                    stringLength: {
                        min: 2,
                        max: 20,
                        message: '昵称为2-20位',
                    },
                }
            },
            email: {
                message: '邮箱不合法',
                validators: {
                    notEmpty: {
                        message: '邮箱不合法'
                    },
                    emailAddress: {
                        message: '请输入正确的邮件地址如：123@qq.com'
                    }
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