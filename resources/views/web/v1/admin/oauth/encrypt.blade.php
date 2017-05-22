<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">查看密钥</h4>
</div>
<br />
<div class="widget p-lg" style="word-break:break-all">
    <p class="m-b-lg docs">
        {{ $remark }}
    </p>
    <small>
        <blockquote>
            密钥: <span class="encrypt_key">{{ $key }}</span>
            &nbsp;&nbsp;
            <a href="javascript:refresh()" title="刷新"><i class="fa fa-refresh"></i></a>
        </blockquote>
    </small>
    
</div>
<script>
function refresh(){
    var id = '{{ $id }}';
    $.confirm({
        content:"您确定要更新当前密钥吗？",
        confirm:function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ route('/oauth/encrypt/refresh')}}",{id:id},function(data){
                if(data.code == '1'){
                    $.alert({
                        content:'重置成功，密钥已更新到界面中，请注意保存',
                        cofirmButtonClass:"btn-success",
                        confirm:function(){
                            $(".encrypt_key").html(data.data);
                        }
                    });
                }else{
                    $.alert(data.data);
                }
            },'json');
        }
    })
}
</script>