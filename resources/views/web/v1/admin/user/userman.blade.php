@extends('web.v1.base.admin')
@section('title', '后台用户管理')
@section('container')
    @parent
    <section class="app-content">
		<form action="{{ route('/admin/user') }}">
			<div class="row">
				<div class="col-xs-12 col-sm-8 col-sm-offset-1">
					<div class="form-group">
						<input type="search" class="form-control promo-search-field" name="s" placeholder="用户名/昵称/邮箱" value="{{ $s }}">
					</div>
				</div>
				<div class="col-xs-12 col-sm-2">
					<input type="submit" class="btn btn-primary btn-block promo-search-submit btn-sm" value="搜索">
				</div>
			</div>
		</form>

        <div class="row">
            <!-- new row -->
            @foreach ($users as $user)
                <div class="col-md-4 col-sm-6">
				<div class="widget">
					<header class="widget-header">
                        <h4 class="widget-title">{{ $user->account }}<a href="{{ route('/admin/user/edit',['id'=>$user->uid]) }}" class="pull-right" ><i class="fa fa-pencil"></i></a></h4>
					</header><!-- .widget-header -->
					<hr class="widget-separator">
					<div class="widget-body p-h-lg">
						<div class="media">
							<div class="media-left">
								<div class="avatar avatar-lg avatar-circle">
									@if($user->avatar != null)
									<img class="img-responsive" src="{{ $user->avatar }}" alt="avatar"/>
									@else 
									<img class="img-responsive" src="{{ URL::asset('/web/v1/assets/images/default.jpg') }}" alt="avatar"/>
									@endif
								</div><!-- .avatar -->
							</div>
							<div class="media-body">
                                <h4 class="media-heading">{{ $user->nickname }} <small style="font-size:10px;color:#ccc;">({{ $user->email }})</small></h4>
                                <small class="media-meta">{{ $user->intro ? : '这家伙一点简介都不写' }}</small><br />
                                <small class="media-meta text-primary">
								@if (count($user->Role) > 0)
									<span class='text-danger'>权限组:</span>
									@foreach ($user->Role as $key=>$role)
										@if($key == count($user->Role)-1)
										{{ $role }}
										@else
										{{ $role }} |
										@endif
									@endforeach
								@endif
								</small><br />
							</div>
						</div>
					</div><!-- .widget-body -->
				</div><!-- .widget -->
			</div>
            @endforeach
            {{ $users->links() }}

        </div><!-- .row -->
    </section>

@stop