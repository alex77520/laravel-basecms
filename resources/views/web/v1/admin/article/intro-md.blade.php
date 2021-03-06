<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">详细信息</h4>
</div>
<div class="modal-body">
   <h2>{{ $post->title }}</h2>
   <hr />
   <p>
    <small>来源：{{$post->source}}</small>
    <small>点赞：{{$post->likes}}</small>
    <small>评论：{{$post->comments}}</small>
    <small>浏览：{{$post->stars}}</small>
   </p>
   <p>
    @if(isset($post->images))
        @foreach ($post->images as $image)
        <div class="avatar avatar-lg">
            <a href="{{ $image }}" data-lightbox="gallery-2" data-title="">
                <img class="img-responsive" src="{{ $image }}" >
            </a>
        </div>
        @endforeach
    @endif
   </p>
   @if ($post->markdown == '1')
    <span id="markdown-true-text" style="display:none;">{{$post->Content->content}}</span>
    <p id="markdown-text"></p>
   @else 
    <p>{{ $post->Content->content }}</p>
   @endif
   <p>
    标签：
    @if($post->tags != null)
        @foreach(explode(',',$post->tags) as $tag)
            <span class="label label-info">{{$tag}}</span>
        @endforeach
    @else 
        无
    @endif
   </p>
</div>
@if ($post->markdown == '1')
<script src="https://cdn.bootcss.com/marked/0.3.6/marked.js"></script>
<script>
    $('#markdown-text').marked($('#markdown-true-text').text());
</script>
@endif