<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">{{$notice->title}}</h4>
</div>
<div class="modal-body">
    <div class="mail-view">
        <div class="row">
            <div class="col-md-12">
                <h4 class="m-b-lg">消息未读/已读列表</h4>
                <p class="m-b-lg docs">
                    已读消息将展示已读时间
                </p>
                <ul class="list-group">
                    @foreach ($notice->Relation as $relation)
                        <li class="list-group-item">
                            @if($relation->is_visit == '1')
                                <span class="badge badge-success">{{date('m-d H:i',$relation->visit_time)}}</span>
                            @else
                                <span class="badge badge-warning">未读</span>
                            @endif
                            {{$relation->User->nickname}} <span class="label label-info">[{{$relation->User->account}}]</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>