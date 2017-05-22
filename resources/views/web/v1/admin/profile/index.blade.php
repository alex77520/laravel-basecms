@extends('web.v1.base.admin')
@section('title', '个人信息')
@section('container')
    @parent
<section class="app-content">
    <div class="profile-header">
        <div class="profile-cover">
            <div class="cover-user m-b-lg">
                <div>
                    <span class="cover-icon"><i class="fa fa-heart-o"></i></span>
                </div>
                <div>
                    <div class="avatar avatar-xl avatar-circle">
                        <a href="javascript:void(0)">
                            <img class="img-responsive" src="../assets/images/221.jpg" alt="avatar"/>
                        </a>
                    </div><!-- .avatar -->
                </div>
            </div>
            <div class="text-center">
                <h4 class="profile-info-name m-b-lg"><a href="javascript:void(0)" class="title-color">John Doe</a></h4>
                <div>
                    这里是简介行不行？
                </div>
            </div>
        </div><!-- .profile-cover -->
        <div class="promo-footer">
            <div class="row no-gutter">
                <div class="col-sm-2 col-sm-offset-3 col-xs-6 promo-tab">
                    <div class="text-center">
                        <small>粉丝</small>
                        <h4 class="m-0 m-t-xs">+2 years</h4>
                    </div>
                </div>
                <div class="col-sm-2 col-xs-6 promo-tab">
                    <div class="text-center">
                        <small>文章条数</small>
                        <h4 class="m-0 m-t-xs">12$ - 25$</h4>
                    </div>
                </div>
                <div class="col-sm-2 col-xs-12 promo-tab">
                    <div class="text-center">
                        <small>收到的赞</small>
                        <div class="m-t-xs">
                            <i class="text-primary fa fa-star"></i>
                            <i class="text-primary fa fa-star"></i>
                            <i class="text-primary fa fa-star"></i>
                            <i class="text-primary fa fa-star"></i>
                            <i class="text-primary fa fa-star-o"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .promo-footer -->
    </div><!-- .profile-header -->
    <hr />
    <div class="row">
       <div class="col-md-8">
            <div class="widget">
                <div class="widget-header p-h-lg p-v-md">
				    <h4 class="widget-title">文章列表<a href="{{ route('/user/Add') }}" data-toggle="modal" data-target="#commonModal" class="btn btn-success pull-right">r添加</a></h4>
				</div>
				<hr class="widget-separator m-0">
				<div class="media stream-post">
                    <div class="media-left">
                        <div class="avatar avatar-lg avatar-circle">
                            <img src="../assets/images/221.jpg" alt="">
                        </div>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading m-t-xs">
                            <a href="javascript:void(0)">John Doe</a>
                            <small class="text-muted">posted an update</small>
                        </h4>
                        <small class="media-meta">Active 14 minute ago</small>
                        <div class="stream-body m-t-xl">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repudiandae neque incidunt cumque, dolore eveniet porro asperiores itaque! Eligendi minus cupiditate molestiae praesentium, facilis, neque saepe, soluta sapiente aliquid modi sunt.</p>
                        </div>
                    </div>
                </div><!-- .stream-post -->

                <div class="media stream-post">
                    <div class="media-left">
                        <div class="avatar avatar-lg avatar-circle">
                            <img src="../assets/images/101.jpg" alt="">
                        </div>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading m-t-xs">
                            <a href="javascript:void(0)">Adam Khaury</a>
                            <small class="text-muted">posted an update</small>
                        </h4>
                        <small class="media-meta">Active 25 minutes ago</small>
                        <div class="stream-body m-t-xl">
                            <a href="../assets/images/original/102.jpg" data-lightbox="feed-img-1">
                                <img class="stream-img" src="../assets/images/102.jpg" alt="">
                            </a>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repudiandae neque incidunt cumque, dolore eveniet porro asperiores itaque! Eligendi minus cupiditate molestiae praesentium, facilis, neque saepe, soluta sapiente aliquid modi sunt.</p>
                        </div>
                    </div>
                </div><!-- .stream-post -->

                <div class="media stream-post">
                    <div class="media-left">
                        <div class="avatar avatar-lg avatar-circle">
                            <img src="../assets/images/204.jpg" alt="">
                        </div>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading m-t-xs">
                            <a href="javascript:void(0)">Dani Smith</a>
                            <small class="text-muted">has birthday</small>
                        </h4>
                        <small class="media-meta">Active now</small>
                        <div class="stream-body m-t-xl">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repudiandae neque incidunt cumque, dolore eveniet porro asperiores itaque! Eligendi minus cupiditate molestiae praesentium, facilis, neque saepe, soluta sapiente aliquid modi sunt.</p>
                        </div>
                    </div>
                </div><!-- .stream-post -->
            </div>
        </div><!-- END column -->

			<div class="col-md-4">
				<div class="row">
					<div class="col-md-12 col-sm-6">
						<div class="widget who-to-follow-widget">
							<div class="widget-header p-h-lg p-v-md">
								<h4 class="widget-title">Who To Follow</h4>
							</div>
							<hr class="widget-separator m-0">
							<div class="media-group">
								<div class="media-group-item b-0 p-h-sm">
									<div class="media">
										<div class="media-left">
											<div class="avatar avatar-md avatar-circle">
												<img src="../assets/images/221.jpg" alt="">
												<i class="status status-online"></i>
											</div>
										</div>
										<div class="media-body">
											<h5 class="media-heading"><a href="javascript:void(0)">John Doe</a></h5>
											<small class="media-meta">Software Engineer</small>
										</div>
									</div>
								</div><!-- .media-group-item -->

								<div class="media-group-item b-0 p-h-sm">
									<div class="media">
										<div class="media-left">
											<div class="avatar avatar-md avatar-circle">
												<img src="../assets/images/101.jpg" alt="">
												<i class="status status-offline"></i>
											</div>
										</div>
										<div class="media-body">
											<h5 class="media-heading"><a href="javascript:void(0)">Adam Khaury</a></h5>
											<small class="media-meta">Web Designer</small>
										</div>
									</div>
								</div><!-- .media-group-item -->

								<div class="media-group-item b-0 p-h-sm">
									<div class="media">
										<div class="media-left">
											<div class="avatar avatar-md avatar-circle">
												<img src="../assets/images/209.jpg" alt="">
												<i class="status status-offline"></i>
											</div>
										</div>
										<div class="media-body">
											<h5 class="media-heading"><a href="javascript:void(0)">John Doe</a></h5>
											<small class="media-meta">Web Developer</small>
										</div>
									</div>
								</div><!-- .media-group-item -->

								<div class="media-group-item b-0 p-h-sm">
									<div class="media">
										<div class="media-left">
											<div class="avatar avatar-md avatar-circle">
												<img src="../assets/images/203.jpg" alt="">
												<i class="status status-away"></i>
											</div>
										</div>
										<div class="media-body">
											<h5 class="media-heading"><a href="javascript:void(0)">Sara Smith</a></h5>
											<small class="media-meta">UI/UX Designer</small>
										</div>
									</div>
								</div><!-- .media-group-item -->

								<div class="media-group-item b-0 p-h-sm">
									<div class="media">
										<div class="media-left">
											<div class="avatar avatar-md avatar-circle">
												<img src="../assets/images/204.jpg" alt="">
												<i class="status status-away"></i>
											</div>
										</div>
										<div class="media-body">
											<h5 class="media-heading"><a href="javascript:void(0)">Dani Smith</a></h5>
											<small class="media-meta">Teacher Assistant</small>
										</div>
									</div>
								</div><!-- .media-group-item -->

								<div class="media-group-item b-0 p-h-sm">
									<div class="media">
										<div class="media-left">
											<div class="avatar avatar-md avatar-circle">
												<img src="../assets/images/202.jpg" alt="">
												<i class="status status-away"></i>
											</div>
										</div>
										<div class="media-body">
											<h5 class="media-heading"><a href="javascript:void(0)">Sally Sally</a></h5>
											<small class="media-meta">Teacher Assistant</small>
										</div>
									</div>
								</div><!-- .media-group-item -->
							</div>
						</div><!-- .widget -->
					</div><!-- END column -->
				</div><!-- .row -->

			</div><!-- END column -->

        
    </div><!-- .row -->
</section>

@stop