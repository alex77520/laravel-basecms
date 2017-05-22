<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">添加员工账号</h4>
</div>
@if ( $user_cnt >= $group->user_num)
<div class="alert alert-danger" role="alert">
    <strong>当前员工账号{{ $user_cnt ."/". $group->user_num}} ，创建已达上限</strong>
</div>
@else 
<div class="alert alert-info" role="alert">
    <strong>员工账号{{ $user_cnt ."/". $group->user_num}}</strong>
</div>
@endif

<form action="{{ route('/admin/organize/user/add') }}" method="post" id="edit-form">
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="用户名" name="username">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="邮箱" name="email">
        </div>
        <div class="form-group">
            <input name="password" type="password" class="form-control" placeholder="新密码" value="" >
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>  保存</button>
    </div>
</form>
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
            },
            password: {
                message: '密码不合法',
                validators: {
                    stringLength: {
                        min: 8,
                        max: 20,
                        message: '密码为8-20位',
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