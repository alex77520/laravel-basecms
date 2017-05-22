@extends('web.v1.base.admin')
@section('title', "修改条目")
@section('container')
    @parent
    @inject('controller', 'App\Http\Controllers\web\v1\Controller')
    <section class="app-content">
        <div class="row">
            <!-- new row -->
            <div class="col-md-12 col-sm-12">
				<div class="widget">
					<header class="widget-header">
						<h4 class="widget-title">修改条目</h4>
					</header><!-- .widget-header -->
					<hr class="widget-separator">
					<div class="widget-body">
						<div class="m-b-lg">
							<small>
							</small>
						</div>
						<form action="{{ route('/slider/item/doEdit') }}" method="post" id="add-form">
                            <input type="hidden" value="{{ $slider->id }}" name="id">
                            {{ csrf_field() }}
							<div class="form-group">
								<label for="ItemName">名称*</label>
								<input type="text" class="form-control" id="ItemName" placeholder="名称" name="name" value="{{ $slider->name }}">
							</div>
							<div class="form-group">
								<label for="ItemURL">跳转URL</label>
								<input type="text" class="form-control" id="ItemURL" placeholder="http://" name="url" value="{{ $slider->uri }}">
							</div>
							<div class="form-group">
								<label for="ItemPic">图片</label>
                                @if ( $slider->fid != null)
                                <input type="hidden" name="img" value="{{ $slider->fid }}"/>
                                @else
                                <input type="hidden" name="img" value=""/>
                                @endif
                                <br />
                                <a href="/image/img" data-toggle="modal" data-function="FillImg" data-target="#commonModal" class="btn btn-success" id="alert">选择图片</a>
                                <div class="img-list" style="margin-top:20px;">
                                @if ( $slider->fid != null)
                                <div class="row">
                                    <div class="col-md-3 col-md-6" style="height:180px;">
                                        <a href="{{ $controller->getImgPath($slider->Files->uuid) }}" data-lightbox="gallery-2" data-title="{{ $slider->Files->name }}">
                                            <img src="{{ $controller->getImgPath($slider->Files->uuid) }}" alt="" style="height:180px;">
                                        </a>
                                    </div>
                                </div>
                                @endif
                                </div>
							</div>
							<div class="form-group">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6">
                                        <label for="ItemPic">生效时间</label>
                                        @if ($slider->down_time != 0)
                                        <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" placeholder="不限时间" name="up_time" value="{{ $slider->up_time == 0 ? :date('Y-m-d h:i:s A',$slider->up_time) }}">
                                        @else
                                        <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" placeholder="不限时间" name="up_time">
                                        @endif
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <label for="ItemPic">失效时间</label>
                                        @if ($slider->down_time != 0)
                                        <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" placeholder="不限时间" name="down_time" value="{{ date('Y-m-d h:i:s A',$slider->down_time)}}">
                                        @else 
                                        <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" placeholder="不限时间" name="down_time">
                                        @endif                                        
                                    </div>
                                </div>
							</div>
                            <div class="form-group">
								<label for="textarea1" class="control-label">备注</label>
									<textarea class="form-control" id="textarea1" placeholder="请输入该条目的备注" name="intro">{{ $slider->intro }}</textarea>
							</div>
							<button type="submit" class="btn btn-primary btn-md">提交</button>
						</form>
					</div><!-- .widget-body -->

				</div><!-- .widget -->
			</div>

        </div><!-- .row -->
    </section>

@stop
@section('js')
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
                message: '名称不合法',
                validators: {
                    notEmpty: {
                        message: '名称不能为空'
                    },
                }
            },
            url: {
                message: 'URL不合法',
                validators: {
                    regexp: {
                        regexp: /(http|https):\/\/[\w+\-_]+(\.[\w\-_]+)/,
                        message: '"http://"或"https://"开头的URL'
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
                            window.location.href="{{ route('/slider/items',['cid'=>$slider->cid]) }}";
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
function FillImg(data){
    var json = data.list;
    if(json.length > 1){
        $.alert('当前限制只能选择一张图片');
    }
    str = '<div class="row"><div class="col-md-3 col-md-6" style="height:180px;">'+
            '<a href="'+json[0].url+'" data-lightbox="gallery-2" data-title="'+json[0].name+'"><img src="'+json[0].url+'" alt="" style="height:180px;"></a>'+
            '</div></div>';
    $("input[name='img']").val(json[0].id);
    $('.img-list').html(str);
}
</script>
@stop