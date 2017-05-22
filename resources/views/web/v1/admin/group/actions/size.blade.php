<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">员工/资源分配</h4>
</div>
<form action="{{ route('/admin/group/setting/size') }}" method="post" id="edit-form">
    {{ csrf_field() }}
    <div class="modal-body">
    <input type="hidden" name="gid" value="{{ $group->gid }}">
        <div class="form-group">
            <label for="">员工数量：</label>
            <input name="user_num" type="number" class="form-control" placeholder="名称" value="{{ $group->user_num }}" >
        </div>
        {{-- <div class="form-group">
            <label for="">资源总大小(MB)：</label>
            <input name="size" type="number" class="form-control" placeholder="资源总大小" value="{{ $group->resource_size }}" >
        </div> --}}
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
            user_num: {
                message: '员工数量不合法',
                validators: {
                    notEmpty: {
                        message: '员工数量不为空'
                    },
                }
            },
            /*
            size: {
                message: '资源可用量不合法',
                validators: {
                    notEmpty: {
                        message: '资源可用量不为空'
                    },
                }
            },
            */
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