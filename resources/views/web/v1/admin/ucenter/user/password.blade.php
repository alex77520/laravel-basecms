<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">修改密码</h4>
</div>
<form action="{{ route('/admin/ucenter/password') }}" method="post" id="edit-form">
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group">
            <input name="oldpassword" type="password" class="form-control" placeholder="当前密码" value="" >
        </div>
        <div class="form-group">
            <input name="password" type="password" class="form-control" placeholder="新密码" value="" >
        </div>
        <div class="form-group">
            <input name="repassword" type="password" class="form-control" placeholder="重复密码" value="">
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
            oldpassword: {
                message: '原密码不合法',
                validators: {
                    stringLength: {
                        min: 8,
                        max: 20,
                        message: '原密码为8-20位',
                    },
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