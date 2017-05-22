<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">修改轮播图</h4>
</div>
<form action="{{ route('/slider/doEdit') }}" method="post" id="edit-form">
{{ csrf_field() }}
    <div class="modal-body">
            <input type="hidden" value='{{ $classify->cid }}' name="id">
            <div class="form-group">
                <input name="name" type="text" class="form-control" placeholder="分类名称" value="{{ $classify->name }}">
            </div>
            <div class="form-group">
                <input name="key" type="text" class="form-control" placeholder="API识别" value="{{ $classify->key }}" >
            </div>
            <div class="form-group">
                <textarea name="intro" class="form-control" placeholder="分类简介">{{ $classify->intro }}</textarea>
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
                message: '分类名称不合法',
                validators: {
                    stringLength: {
                        min: 2,
                        max: 8,
                        message: '名称为2-8位',
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