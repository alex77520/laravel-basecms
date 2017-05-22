@extends('web.v1.base.admin')
@section('title', '普通模式')
@section('css')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/default.min.css">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/web/v1/libs/bower/eonasdan-bootstrap-datetimepicker/build') }}/css/bootstrap-datetimepicker.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('container')
@parent
<section class="app-content">
    <div class="row">
        <div class="col-md-12">
            <div class="mail-toolbar m-b-lg">
                <h3>普通模式</h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="what" style="display:none;">{{$content->content}}</div>
        <div class="col-md-12 abc">
        </div><!-- END column -->
    </div><!-- .row -->
</section>
@stop

@section('js')
<script type="text/javascript" src="{{ URL::asset('/web/v1/libs/bower/eonasdan-bootstrap-datetimepicker/build') }}/js/bootstrap-datetimepicker.min.js"></script>
{{-- <script src="https://cdn.bootcss.com/showdown/1.3.0/showdown.min.js"></script> --}}
<script src="https://cdn.bootcss.com/marked/0.3.6/marked.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/highlight.min.js"></script>
<script>
hljs.initHighlightingOnLoad();
$(function() {  
    //var converter = new showdown.Converter();  
    var text      = $('.what').text();
    //html      = converter.makeHtml(text);  
    $('.abc').html(marked(text));
}); 
</script>
@stop