<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">添加账户</h4>
</div>
<form action="{{ route('/user/doAdd') }}" method="post" id="add-form">
{{ csrf_field() }}
    <div class="modal-body">
            <div class="form-group">
                <input name="username" type="text" class="form-control" placeholder="用户名" >
            </div>
            <div class="form-group">
                <input name="name" type="text" class="form-control" placeholder="昵称" />
            </div>
            <div class="form-group">
                <input name="email" type="email" class="form-control" placeholder="邮箱账号" >
            </div>
            <div class="form-group">
                <input name="password" type="password" class="form-control" placeholder="密码|如不修改请勿填写">
            </div>
            <div class="form-group">
                <input name="repassword" type="password" class="form-control" placeholder="重复密码">
            </div>
            <div class="form-group">
                <div>
                    账户状态：<br />
                    <label class="radio-inline">
                        <input type="radio" name="status" id="inlineRadio1" value="1" checked> 正常
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" id="inlineRadio2" value="2" > 冻结
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div>
                    角色：<br />
                    @foreach( $roles as $role )
                        <label class="checkbox-inline">
                            <input type="checkbox" name="roles[]" value="{{ $role->role_id }}"> {{ $role->role_name }}
                        </label>
                    @endforeach
                </div>
            </div>
            {{-- <textarea name="intro" cols="30" rows="5" class="form-control" placeholder="个人简介"></textarea> --}}
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>  保存</button>
    </div>
</form>
<script>
$(function(){
    $('#add-form').bootstrapValidator({
        message: '填写的数据不合法',
        feedbackIcons: {
            valid: 'fa fa-ok',
            invalid: 'fa fa-remove',
            validating: 'fa fa-refresh'
        },
        fields: {
            username: {
                message: '用户名不合法',
                validators: {
                    notEmpty: {
                        message: '用户名不能为空'
                    },
                    stringLength: {
                        min: 3,
                        max: 10,
                        message: '用户名限制3-10位',
                    },
                }
            },
            password: {
                message: '密码不合法',
                validators: {
                    notEmpty: {
                        message: '密码不能为空'
                    },
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