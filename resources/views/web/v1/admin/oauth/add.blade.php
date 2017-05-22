<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">添加客户端</h4>
</div>
<form action="{{ route('/oauth/doAdd') }}" method="post" id="add-form">
{{ csrf_field() }}
    <div class="modal-body">
            <div class="form-group">
                <input name="name" type="text" class="form-control" placeholder="客户端标识" >
            </div>
            <div class="form-group">
                <input name="redirect_uri" type="text" class="form-control" placeholder="回调URI，可不填" >
            </div>
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
            name: {
                message: '客户端标识不合法',
                validators: {
                    notEmpty: {
                        message: '客户端标识不为空'
                    },
                    stringLength: {
                        min: 3,
                        max: 10,
                        message: '客户端标识限制3-10位',
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
                    var client_id = data.data.client_id;
                    var client_secret = data.data.client_secret;
                    $.alert({
                        content:'添加成功',
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