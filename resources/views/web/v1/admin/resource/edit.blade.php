<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">修改文件信息</h4>
</div>
<div class="modal-body">
    <form action="{{ route('/admin/resource/edit') }}" method="post" id="edit-form">
        {{ csrf_field() }}
        <input type="hidden" value='{{ $resource->id }}' name="id">
        <div class="form-group">
            <input name="name" type="text" class="form-control" placeholder="文件名称" value='{{ $resource->name }}'>
        </div>
        <div class="form-group">
            <select class="form-control" name="cid">
                @foreach($classifys as $classify)
                    @if($classify->id == $resource->cid )
                        <option value="{{ $classify->id }}" selected="selected">{{ $classify->name }}</option>
                    @else
                        <option value="{{ $classify->id }}">{{ $classify->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <input type="text" class="form-control fileext" value='{{ $resource->type }}' disabled="disabled" >
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
                    message: '文件名称不合法',
                    validators: {
                        notEmpty: {
                            message: '文件名称不能为空'
                        },
                        stringLength: {
                            min: 2,
                            max: 30,
                            message: '文件名称限制2-30位',
                        },
                    }
                },
                cid: {
                    message: '分类不合法',
                    validators: {
                        notEmpty: {
                            message: '分类不能为空'
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
</div>
