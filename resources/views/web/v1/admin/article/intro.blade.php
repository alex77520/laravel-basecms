<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">详细信息</h4>
</div>
<div class="modal-body">
   <h2>{{ $post->title }}</h2>
   <p>
    <small>来源：{{$post->source}}</small>
    <small>点赞：{{$post->likes}}</small>
    <small>评论：{{$post->comments}}</small>
    <small>浏览：{{$post->stars}}</small>
   </p>
   <p>
   封面图：
    @if(isset($post->images))
        @foreach ($post->images as $image)
        <div class="avatar avatar-lg">
            <a href="{{ $image }}" data-lightbox="gallery-2" data-title="">
                <img class="img-responsive" src="{{ $image }}" >
            </a>
        </div>
        @endforeach
    @else
        无
    @endif
   </p>
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
   <p>
   定时发布：
    @if ($post->interval != 0)
            @if($post->interval < time())
                已于 <b class="text-primary">{{ date('Y-m-d H:i',$post->interval)}} </b> 发布！
            @else
                将于 <b class="text-primary">{{ date('Y-m-d H:i',$post->interval)}}</b> 发布~
            @endif
    @else
            即时发布
    @endif
   </p>
</div>