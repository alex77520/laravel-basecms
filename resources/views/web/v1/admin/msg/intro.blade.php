<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">{{$notice->title}}</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="widget">
                <header class="widget-header">
                    <h4 class="widget-title">{{$notice->title}}</h4>
                </header><!-- .widget-header -->
                <hr class="widget-separator">
                <div class="widget-body">
                    <p>内容：</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;{{$notice->content}}</p>
                    <hr class="widget-separator">
                    <div class="media">
                        <div class="media-left">
                            <div class="avatar avatar-sm avatar-circle">
                                <img class="img-responsive" src="{{$notice->User->avatar}}" alt="avatar">
                            </div>
                        </div>
                        <div class="media-body">
                            <div class="m-b-sm">
                                <h5 class="m-0 inline-block m-r-lg">
                                    <a href="#" class="title-color">
                                        {{$notice->User->nickname}}
                                    </a>
                                </h5>
                            </div>
                            <p>
                                <b>
                                    From:
                                </b>
                                {{$notice->created_at}}
                            </p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>