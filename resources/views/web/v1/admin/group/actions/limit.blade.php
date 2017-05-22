<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">权限设置</h4>
</div>
<form action="{{ route('/admin/group/setting/limit') }}" method="post" id="edit-form">
    {{ csrf_field() }}
    <div class="modal-body">
        <input type="hidden" name="gid" value="{{ $group->gid }}">
        <div class="form-group">
            <div>
                发布文章是否需要审核：
                <label class="radio-inline">
                    @if($group->post_audit == '1')
                        <input type="radio" name="audit" id="inlineRadio1" value="1" checked> 需要
                    @else
                        <input type="radio" name="audit" id="inlineRadio1" value="1"> 需要
                    @endif
                </label>
                <label class="radio-inline">
                    @if($group->post_audit == '1')
                        <input type="radio" name="audit" id="inlineRadio1" value="2"> 不需要
                    @else
                        <input type="radio" name="audit" id="inlineRadio1" value="2" checked> 不需要
                    @endif
                </label>
            </div>
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
            name: {
                message: '名称不合法',
                validators: {
                    stringLength: {
                        min: 2,
                        max: 20,
                        message: '名称为2-20位',
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