@inject('controller', 'App\Http\Controllers\web\Controller')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.img-responsive{
	width:128px;
	height:128px;
}
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">图片选择器</h4>
</div>
<div class="modal-body">
    <div class="m-b-lg white">
        <div class="btn-group" role="group">
        <a href="javascript:chooseClassify(0)" class="btn btn-default btn-sm classifys classify-0">所有图片</a>
        @foreach($classifys as $classify)
            <a href="javascript:chooseClassify('{{ $classify->id }}')" class="btn btn-default btn-sm classifys classify-{{$classify->id}}">{{ $classify->name }}</a>
        @endforeach
        </div>
		<div class="gallery row modal-img-list " style="overflow:scroll;max-height:400px;width:auto;">
			
		</div>
		<div class="row" style="height:50px;line-height:50px;">
			<div class="col-xs-12 col-sm-12 col-md-12 text-center" >
				<button type="button" class="btn btn-success gouxuan">确认选择</button>
			</div>
		</div>
    </div>
</div>
<script src="{{ URL::asset('/web/plugins/ajaxfileupload') }}/ajaxfileupload.js" type="text/javascript"></script>
<script>
var currPage = 1;
var cid = 0;
$(document).ready(function(){
    // 加载图片
	loadImg();
	var $function = $("#"+"{{ $id }}").data('function');
    // 添加手动加载的触发事件
	$(document).on('click' , '.load' , function(){
		loadImg();
	})
    // 为勾选按钮添加触发事件
	$(document).on('click','.gouxuan',function(){
		var json = {
			'list':[

			]
		};
		$('input[name="image"]').each(function(){
			if($(this).is(':checked')){
				var t = {
					'id':$(this).val(),
					'url':$(this).attr('rel'),
					'name':$(this).attr('picname')
				};
				json.list.push(t);
			}
		})
		//console.log(json.list);
		$("#commonModal").modal().callbackData($function,json); //指定回调方法名与回调参数
		$("#commonModal").modal('hide');
		// 下面两行代码是解决：第二次点开modal的时候，会导致遮罩层依旧存在，手动清除遮罩层
		$(".modal-backdrop").remove();
		$("body").removeClass('modal-open'); 
	});
});
function chooseClassify(id){
    cid = id;
    currPage = 1;
    console.log(cid);
    clearImg();
    loadImg();
}
function clearImg(){
    $(".modal-img-list").html('');
}
//加载图片
function loadImg(){
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.post("{{ $load_url }}",{page:currPage,cid:cid},function(data){
		if(data.code == '1'){
			$(".load-text").remove();
			var json = data.data;
			str = "";
			for(var i = 0;i < json.length;i++){
				str += getImgText(json[i].id,json[i].url,json[i].name);
			}
			if(json.length >= 8){
				str += '<div class="col-xs-12 col-sm-12 col-md-12 load-text">' +
					'<div class="text-center load"><a href="javascript:void(0);">加载下一页</a></div>' +
				'</div>';
			}
			$(".modal-img-list").append(str);
			currPage++;
		}else{
			Toast('没有更多了');
		}
	})
}
//拼装图片路径
function getImgText(id,url,name){
	return '<div class="col-xs-6 col-sm-4 col-md-3">' + 
				'<div class="gallery-item">' + 
					'<div class="thumb">' +
						'<a href="'+url+'" data-lightbox="gallery-2" data-title="'+name+'">' + 
							'<img class="img-responsive" src="'+url+'" alt="">' +
						'</a>' + 
					'</div>' + 
					'<div class="caption"><input type="checkbox" name="image" value="'+id+'" rel="'+url+'" picname="'+name+'"/>&nbsp;'+name+' </div>' +
				'</div>' +
			'</div>';
}
</script>