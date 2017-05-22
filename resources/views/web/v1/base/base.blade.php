<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>后台管理 - @yield('title') </title>
	<meta name="description" content="微信小程序,后台管理,微蚁儿" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="{{ URL::asset('favicon.ico') }}" rel="Shortcut Icon">
	<link rel="stylesheet" href="{{ URL::asset('/web/v1/libs/bower/font-awesome/css') }}/font-awesome.min.css">
	
	<link rel="stylesheet" href="{{ URL::asset('/web/v1/libs/bower/MaterialDesign-Webfont-master/css') }}/materialdesignicons.css">
	<link rel="stylesheet" href="{{ URL::asset('/web/v1/libs/bower/material-design-iconic-font/dist/css') }}/material-design-iconic-font.css">
	<link rel="stylesheet" href="{{ URL::asset('/web/v1/libs/bower/animate.css') }}/animate.min.css">
	<link rel="stylesheet" href="{{ URL::asset('/web/v1/assets/css') }}/bootstrap.css">
	<link rel="stylesheet" href="{{ URL::asset('/web/v1/assets/css') }}/app.css">
	<link rel="stylesheet" href="{{ URL::asset('/web/plugins/formvalidator') }}/bootvalid.css">
	<link rel="stylesheet" href="{{ URL::asset('/web/plugins/jquery-confirm') }}/jquery.confirm.css">
	{{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300"> --}}
	@yield('css');
</head>
@inject('controller', 'App\Http\Controllers\web\Controller')
<body class="sb-left  theme-primary pace-done sb-folded">
<!--============= start main area -->

<!-- APP NAVBAR ==========-->
<nav id="app-navbar" class="app-navbar p-l-lg p-r-md primary">
	<div id="navbar-header" class="pull-left">
		<button id="aside-fold" class="hamburger visible-lg-inline-block hamburger--arrowalt js-hamburger" type="button">
			<span class="hamburger-box">
				<span class="hamburger-inner"></span>
			</span>
		</button>
		<button id="aside-toggle" class="hamburger hidden-lg hamburger--spin js-hamburger" type="button">
			<span class="hamburger-box">
				<span class="hamburger-inner"></span>
			</span>
		</button>
		<h5 id="page-title" class="visible-md-inline-block visible-lg-inline-block m-l-md">@yield('title')</h5>
	</div>

	<div>
		<ul id="top-nav" class="pull-right">
			<li class="nav-item dropdown">
				<a href="javascript:void(0)" id="navbar-search-open" class="navbar-search-open">
					<i class="mdi mdi-hc-lg mdi-magnify"></i>
				</a>
			</li>
			<li class="nav-item dropdown">
				<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-hc-lg mdi-snapchat"></i></a>
				<div class="media-group dropdown-menu animated flipInY">
					<a href="javascript:void(0)" class="media-group-item">
						<div class="media">
							<div class="media-left">
								<div class="avatar avatar-xs avatar-circle">
									<img src="{{ URL::asset('/web/v1/assets/images/221.jpg') }}" alt="">
									<i class="status status-online"></i>
								</div>
							</div>
							<div class="media-body">
								<h5 class="media-heading">John Doe</h5>
								<small class="media-meta">Active now</small>
							</div>
						</div>
					</a><!-- .media-group-item -->

					<a href="javascript:void(0)" class="media-group-item">
						<div class="media">
							<div class="media-left">
								<div class="avatar avatar-xs avatar-circle">
									<img src="{{ URL::asset('/web/v1/assets/images/205.jpg') }}" alt="">
									<i class="status status-offline"></i>
								</div>
							</div>
							<div class="media-body">
								<h5 class="media-heading">John Doe</h5>
								<small class="media-meta">2 hours ago</small>
							</div>
						</div>
					</a><!-- .media-group-item -->

					<a href="javascript:void(0)" class="media-group-item">
						<div class="media">
							<div class="media-left">
								<div class="avatar avatar-xs avatar-circle">
									<img src="{{ URL::asset('/web/v1/assets/images/207.jpg') }}" alt="">
									<i class="status status-away"></i>
								</div>
							</div>
							<div class="media-body">
								<h5 class="media-heading">Sara Smith</h5>
								<small class="media-meta">idle 5 min ago</small>
							</div>
						</div>
					</a><!-- .media-group-item -->

					<a href="javascript:void(0)" class="media-group-item">
						<div class="media">
							<div class="media-left">
								<div class="avatar avatar-xs avatar-circle">
									<img src="{{ URL::asset('/web/v1/assets/images/211.jpg') }}" alt="">
									<i class="status status-away"></i>
								</div>
							</div>
							<div class="media-body">
								<h5 class="media-heading">Donia Dyab</h5>
								<small class="media-meta">idle 5 min ago</small>
							</div>
						</div>
					</a><!-- .media-group-item -->
				</div>
			</li>
			<li class="nav-item dropdown">
				<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-hc-lg mdi-settings"></i></a>
				<ul class="dropdown-menu animated flipInY">
					<li><a href="{{ route('/admin/ucenter/userinfo/edit') }}"><i class="mdi m-r-md mdi-hc-lg mdi-account-box"></i>个人资料</a></li>
					<li><a href="{{ route('/admin/ucenter/password') }}"  data-toggle="modal" data-target="#commonModal"><i class="mdi m-r-md mdi-hc-lg mdi-account-key"></i>修改密码</a></li>
					<li><a href="{{ route('/logout') }}"><i class="mdi m-r-md mdi-hc-lg mdi-power"></i>退出登录</a></li>
				</ul>
			</li>
		</ul>
	</div>

	<!-- navbar search -->
	<div id="navbar-search" class="navbar-search">
		<form action="#">
			<span class="search-icon"><i class="fa fa-search"></i></span>
			<input class="search-field" type="search" placeholder="search..."/>
		</form>
		<button id="search-close" class="search-close">
			<i class="fa fa-close"></i>
		</button>
	</div><!-- END navbar search -->
</nav>
<!--========== END app navbar -->


<!-- APP MAIN ==========-->
<main id="app-main" class="app-main">
	<div class="wrap">
		@yield('container')
	</div><!-- .wrap -->
	
	<!-- APP FOOTER -->
	<div class="wrap p-t-0">
		<footer class="app-footer">
			<div class="clearfix">
				<ul class="footer-menu pull-right">
					<li><a href="javascript:void(0)">版权</a></li>
					<li><a href="javascript:void(0)">帮助中心</a></li>
					<li><a href="javascript:void(0)">团队成员</a></li>
				</ul>
				<div class="copyright pull-left">Copyright Veeyer 2013 - 2017 &copy;</div>
			</div>
		</footer>
	</div>
	<!-- /#app-footer -->
</main>
<!--========== END app main -->

	<div class="modal fade" id="commonModal" tabindex="-1" role="dialog" data-backdrop="static" >
		<div class="modal-dialog">
			<div class="modal-content">
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<!-- /#app-customizer -->
	
    <script src="{{ URL::asset('/web/v1/libs/bower/jquery/dist') }}/jquery.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/v1/libs/bower/jquery-ui') }}/jquery-ui.min.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/v1/libs/bower/jQuery-Storage-API') }}/jquery.storageapi.min.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/v1/libs/bower/bootstrap-sass/assets/javascripts') }}/bootstrap.js" type="text/javascript"></script>
    {{-- <script src="{{ URL::asset('/web/plugins') }}/bootstrap.modal.js" type="text/javascript"></script> --}}
    <script src="{{ URL::asset('/web/v1/libs/bower/superfish/dist/js/') }}/hoverIntent.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/v1/libs/bower/superfish/dist/js/') }}/superfish.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/v1/libs/bower/jquery-slimscroll') }}/jquery.slimscroll.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/v1/libs/bower/perfect-scrollbar/js') }}/perfect-scrollbar.jquery.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/v1/libs/bower/PACE') }}/pace.min.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/plugins/formvalidator') }}/bootvalid.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/plugins/jquery-confirm') }}/jquery.confirm.js" type="text/javascript"></script>

    <script src="{{ URL::asset('/web/v1/assets/js') }}/library.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/v1/assets/js') }}/plugins.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/v1/assets/js') }}/app.js" type="text/javascript"></script>


    <script src="{{ URL::asset('/web/v1/libs/bower/moment') }}/moment.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/v1/libs/bower/fullcalendar/dist') }}/fullcalendar.min.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/v1/assets/js') }}/fullcalendar.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/v1/assets/js') }}/common.js" type="text/javascript"></script>
	<script>
	$(function(){
		$("#commonModal").on("hidden.bs.modal",function(){
			$(this).removeData("bs.modal"); 
		});
	})
	</script>
	@yield('js')
</body>
</html>
