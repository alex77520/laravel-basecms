@inject('controller', 'App\Http\Controllers\web\v1\Controller')
<style>
.img-responsive{
	width:128px;
	height:128px;
}
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">图片选择器
	<a type="button" class="btn btn-info pull-right upload-btn" style="margin-right:35px;" href="javascript:upload();">上传图片</a>
	<input type="file" name="upload_dialog_img" style="display:none;" onchange="javascript:doUpload();" id="upload_dialog_img"/>
	</h4>
</div>
<div class="modal-body">
    <div class="m-b-lg white">
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
$(document).ready(function(){
	loadImg(currPage);
	var $function = $("#"+"{{ $id }}").data('function');
	$(document).on('click' , '.load' , function(){
		loadImg(currPage);
	})
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
//加载图片
function loadImg(){
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.post("{{ route('/image/getImg') }}",{page:currPage},function(data){
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
//上传按钮点击
function upload(){
	$("input[name='upload_dialog_img']").trigger('click');
}
//执行上传
function doUpload(){
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajaxFileUpload({  
		url:"{{ route('/image/uploadImg') }}",  
		secureuri:false,  
		fileElementId:'upload_dialog_img',//file标签的id  
		dataType: 'json',//返回数据的类型  
		data:{type:"image"},//一同上传的数据  
		success: function (msg) {
			//console.log(msg);
			if(msg.code == 1){
				$(".modal-img-list").prepend(getImgText(msg.data.id,msg.data.url,msg.data.name));
			}else{
				Toast(msg.data);
			}
		},
		error: function (data, status, e) {  
			console.log(e);  
		}  
	});
}
</script>