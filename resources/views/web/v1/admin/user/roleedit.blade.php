<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">角色修改</h4>
</div>
<form action="{{ route('/admin/role/edit') }}" method="post" id="edit-form">
{{ csrf_field() }}
    <div class="modal-body">
            <input type="hidden" value='{{ $role->id }}' name="id">
            <div class="form-group">
                <input name="name" type="text" class="form-control" placeholder="角色名称" value='{{ $role->name }}'>
            </div>
            <div class="form-group">
                <textarea name="intro" cols="30" rows="5" class="form-control" placeholder="角色简介">{{ $role->intro }}</textarea>
            </div>
           
            <div class="form-group">
                <div>
                    @foreach( $limitlist as $limit )
                        <hr />
                        <h5>{{ $limit['Intro'] }}</h5>
                        @foreach( $limit['List'] as $key=>$list )
                                @if (in_array($key,$role->limits))
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="limit[]" value="{{ $key }}" checked> {{ $list }}
                                    </label>
                                @else
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="limit[]" value="{{ $key }}"> {{ $list }}
                                    </label>
                                @endif
                        @endforeach
                    @endforeach
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
                message: '角色名不合法',
                validators: {
                    notEmpty: {
                        message: '角色名不能为空'
                    },
                    stringLength: {
                        min: 2,
                        max: 10,
                        message: '角色名限制2-10位',
                    },
                }
            },
            intro: {
                message: '简介不合法',
                validators: {
                    stringLength: {
                        min: 1,
                        max: 50,
                        message: '简介仅限1-50位',
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