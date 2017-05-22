@extends('web.v1.base.admin')
@section('title', '后台用户管理')
@section('container')
    @parent
    <section class="app-content">
        <div class="row">
            <!-- new row -->
            <div class="modal-header">
                <h4 class="modal-title">用户信息修改</h4>
            </div>
            <form action="{{ route('/admin/user/edit') }}" method="post" id="edit-form">
                {{ csrf_field() }}
                <div class="modal-body">
                        <input type="hidden" value='{{ $user->uid }}' name="uid">
                        <div class="form-group">
                            <label for="">用户名：</label>
                            <input name="username" type="text" class="form-control" placeholder="用户名" disabled="disabled" value='{{ $user->account }}'>
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
                            <label for="">密码：</label>
                            <input name="password" type="password" class="form-control" placeholder="密码|如不修改请勿填写">
                        </div>
                        <div class="form-group">
                            <label for="">重复密码：</label>
                            <input name="repassword" type="password" class="form-control" placeholder="重复密码">
                        </div>
                        <div class="form-group">
                            <label for="">简介：</label>
                            <textarea name="intro" id="" cols="30" rows="5" class="form-control" >{{ $user->intro }}</textarea>
                        </div>
                        <div class="form-group">
                            <div>
                                <label for="">账户状态：</label>
                                <label class="radio-inline">
                                    <input type="radio" name="power" id="inlineRadio1" value="1" @if ($user->power == '1') checked @endif> 正常
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="power" id="inlineRadio2" value="2" @if ($user->power != '1') checked @endif > 冻结
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <label for="">角色：</label><br />
                                @foreach( $roles as $role )
                                    @if ( in_array($role->id,$userRole) )
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" checked> {{ $role->name }}
                                    </label>
                                    @else
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"> {{ $role->name }}
                                    </label>
                                    @endif
                                @endforeach
                            </div>
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
            password: {
                message: '密码不合法',
                validators: {
                    stringLength: {
                        min: 8,
                        max: 20,
                        message: '密码为8-20位',
                    },
                }
            },
			repassword: {
				message: '重复密码不合法',
				validators: {
                    identical: {
                        field: 'password',
                        message: '两次输入的密码不一致'
                    }
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
</script>
@stop