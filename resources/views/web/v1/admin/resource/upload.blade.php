<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">上传文件</h4>
</div>
<div class="modal-body">
    <div class="widget p-lg" style="word-break:break-all">
        <p class="m-b-lg docs">
            <form method="post" action="#">
                <div class="form-group">
                    <label>文件：</label>
                    <div class="controls">
                        <input type="file" id="aetherupload-file" onchange="AetherUpload.upload('file')"  class="form-control"/>
                        <div class="progress progress-bar-striped" style="height: 6px;margin-bottom: 2px;margin-top: 10px;width: 200px;">
                            <div id="aetherupload-progressbar" style="background:#459bdc;height:6px;width:0;"></div>
                        </div>
                        <span style="font-size:12px;color:#aaa;" id="aetherupload-output">等待上传</span>
                    </div>
                </div>
            </form>
        </p>
    </div>
    <form action="{{ route('/admin/resource/edit') }}" method="post" id="edit-form" style="display:none;">
        {{ csrf_field() }}
        <input type="hidden" value='' name="id">
        <div class="form-group">
            <input name="name" type="text" class="form-control" placeholder="文件名称" value=''>
        </div>
        <div class="form-group">
            <select class="form-control" name="cid">
                @foreach($classifys as $classify)
                    <option value="{{ $classify->id }}">{{ $classify->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <input type="text" class="form-control fileext" value='' disabled="disabled" >
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>  保存</button>
        </div>
    </form>
<script src="{{ URL::asset('js/aetherupload.js') }}"></script><!--need to have aetherupload.js-->
<script>
    AetherUpload.success = function () {
        var _this = this;
        console.log(_this);
        $.post("{{ route('/admin/resource/upload')}}",{
            name:_this.fileName,
            path:_this.group + "/" + _this.subDir,
            filename:_this.uploadBaseName + '.' + _this.uploadExt,
            size:_this.fileSize,
            type:_this.uploadExt,
        },function(json){
            if(json.code == "1"){
                $("input[name='name']").val(_this.fileName);
                //$("select[name='cid']").val(json.data.cid);
                $("input[name='id']").val(json.data.id);
                $('.fileext').val(_this.uploadExt);
                $('#edit-form').show();
            }else{
                $.alert('服务器异常，请刷新页面重试');
            }
        })
    }
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
