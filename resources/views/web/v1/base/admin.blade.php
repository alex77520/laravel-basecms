<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>后台管理 - @yield('title') </title>
	<meta name="description" content="微信小程序,后台管理,微蚁儿" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
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

<!-- APP ASIDE ==========-->
<aside id="app-aside" class="app-aside left light in folded">
	<header class="aside-header">
		<div class="animated">
			<a href="../index.html" id="app-brand" class="app-brand">
				<span id="brand-icon" class="brand-icon"><i class="fa fa-gg"></i></span>
				<span id="brand-name" class="brand-icon foldable">Veeyer</span>
			</a>
		</div>
	</header><!-- #sidebar-header -->
	
	<div class="aside-user">
		<!-- aside-user -->
		<div class="media">
			<div class="media-left">
				<div class="avatar avatar-md avatar-circle">
					<a href="javascript:void(0)">
						@if($controller->getWebUserInfo()['avatar'] != null)
						<img class="img-responsive" src="{{ $controller->getWebUserInfo()['avatar'] }}" alt="avatar"/>
						@else 
						<img class="img-responsive" src="{{ URL::asset('/web/v1/assets/images/221.jpg') }}" alt="avatar"/>
						@endif
					</a>
				</div><!-- .avatar -->
			</div>
			<div class="media-body">
				<div class="foldable">
					<h5><a href="javascript:void(0)" class="username">{{ $controller->getWebUserInfo()['nickname'] }}</a></h5>
					<ul>
						<li class="dropdown">
							<a href="javascript:void(0)" class="dropdown-toggle usertitle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<small>快捷操作</small>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu animated flipInY">
								<li>
									<a class="text-color" href="../../index.html">
										<span class="m-r-xs"><i class="mdi mdi-home"></i></span>
										<span>首页</span>
									</a>
								</li>
								<li>
									<a class="text-color" href="profile.html">
										<span class="m-r-xs"><i class="mdi mdi-account"></i></span>
										<span>个人主页</span>
									</a>
								</li>
								<li>
									<a class="text-color" href="settings.html">
										<span class="m-r-xs"><i class="mdi mdi-settings"></i></span>
										<span>设置</span>
									</a>
								</li>
								<li role="separator" class="divider"></li>
								<li>
									<a class="text-color" href="{{ route('/logout') }}">
										<span class="m-r-xs"><i class="mdi mdi-power"></i></span>
										<span>退出登录</span>
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /aside-user -->
	</div><!-- #aside-user -->

	<div class="aside-scroll">
		<div id="aside-scroll-inner" class="aside-scroll-inner">
			<ul class="aside-menu aside-left-menu">
				<li class="menu-item">
					<a href="{{ route('/admin') }}" class="menu-link">
						<span class="menu-icon"><i class="mdi mdi-view-dashboard mdi-hc-lg"></i></span>
						<span class="menu-text foldable">主页</span>
					</a>
				</li><!-- .menu-item -->
				@if ($controller->HasLimit('UserSee') || $controller->HasLimit('RoleSee') || $controller->HasLimit('GroupSee'))
					<li class="menu-item has-submenu">
						<a href="javascript:void(0)" class="menu-link submenu-toggle">
							<span class="menu-icon"><i class="mdi mdi-menu mdi-hc-lg"></i></span>
							<span class="menu-text foldable">站点管理</span>
							<span class="menu-caret foldable"><i class="mdi mdi-hc-sm mdi-chevron-right"></i></span>
						</a>
						<ul class="submenu">
							@if ($controller->HasLimit('GroupSee') !== false)
								<li><a href="{{ route('/admin/group') }}">站点配置</a></li>
							@endif
							@if ($controller->HasLimit('GroupSee') !== false)
								<li><a href="{{ route('/admin/group') }}">机构管理</a></li>
							@endif
							@if ($controller->HasLimit('UserSee') !== false)
								<li><a href="{{ route('/admin/user') }}">用户管理</a></li>
							@endif
							@if ($controller->HasLimit('RoleSee') !== false)
								<li><a href="{{ route('/admin/role') }}">角色管理</a></li>
							@endif
							@if ($controller->HasLimit('RoleSee') !== false)
								<li><a href="{{ route('/admin/role') }}">会员管理</a></li>
							@endif
							@if ($controller->HasLimit('MsgSee') !== false)
								<li><a href="{{ route('/admin/notice') }}">站内信</a></li>
							@endif
						</ul>
					</li>
				@endif
				@if ($controller->HasLimit('PostClassifySee') || $controller->HasLimit('PostSee') || $controller->HasLimit('SliderSee') )
					<li class="menu-item has-submenu">
						<a href="javascript:void(0)" class="menu-link submenu-toggle">
							<span class="menu-icon"><i class="mdi mdi-layers mdi-hc-lg"></i></span>
							<span class="menu-text foldable">资料管理</span>
							<span class="menu-caret foldable"><i class="mdi mdi-hc-sm mdi-chevron-right"></i></span>
						</a>
						<ul class="submenu">
							@if ($controller->HasLimit('PostClassifySee') !== false)
								<li><a href="{{ route('/admin/posts/classify') }}">分类管理</a></li>
							@endif
							@if ($controller->HasLimit('PostSee') !== false)
								<li><a href="{{ route('/admin/posts') }}">文章管理</a></li>
							@endif
							@if ($controller->HasLimit('SliderSee') !== false)
								<li><a href="{{ route('/admin') }}">轮播图管理</a></li>
							@endif
						</ul>
					</li>
				@endif
				@if ($controller->isGroupAdmin())
					<li class="menu-item has-submenu">
						<a href="javascript:void(0)" class="menu-link submenu-toggle">
							<span class="menu-icon"><i class="mdi mdi-sitemap mdi-hc-lg"></i></span>
							<span class="menu-text foldable">机构信息</span>
							<span class="menu-caret foldable"><i class="mdi mdi-hc-sm mdi-chevron-right"></i></span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('/admin/organize') }}">基本信息</a></li>
							<li><a href="{{ route('/admin/organize/user') }}">员工管理</a></li>
							<li><a href="{{ route('/admin/organize/log') }}">操作日志</a></li>
							<li><a href="{{ route('/admin/organize/notice') }}">组内信</a></li>
						</ul>
					</li>
				@endif
				<li class="menu-item has-submenu">
					<a href="javascript:void(0)" class="menu-link submenu-toggle">
						<span class="menu-icon"><i class="mdi mdi-menu mdi-hc-lg"></i></span>
						<span class="menu-text foldable">资源中心</span>
						<span class="menu-caret foldable"><i class="mdi mdi-hc-sm mdi-chevron-right"></i></span>
					</a>
					<ul class="submenu">
						<li><a href="{{ route('/admin/article') }}">文章管理</a></li>
						<li><a href="{{ route('/admin/resource') }}">文件管理</a></li>
						<li><a href="{{ route('/admin/msg') }}">我的通知</a></li>
					</ul>
				</li>
				<li class="menu-item has-submenu">
					<a href="javascript:void(0)" class="menu-link submenu-toggle">
						<span class="menu-icon"><i class="mdi mdi-apps mdi-hc-lg"></i></span>
						<span class="menu-text foldable">快捷操作</span>
						<span class="menu-caret foldable"><i class="mdi mdi-hc-sm mdi-chevron-right"></i></span>
					</a>
					<ul class="submenu">
						<li><a href="{{ route('/admin/article/choose') }}">发布文章</a></li>
					</ul>
				</li>
			</ul>
			<hr>
			
		</div><!-- .aside-scroll-inner -->
	</div><!-- #aside-scroll -->
</aside>
<!--========== END app aside -->

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
				<a href="javascript:void(0)" class="dropdown-toggle common_msg_list" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-hc-lg mdi-snapchat"></i></a>
				<div class="media-group dropdown-menu animated flipInY " id="common_msg_list">
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

	<div class="modal fade" id="commonModal-lg" tabindex="-1" role="dialog" data-backdrop="static" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
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
    <script src="{{ URL::asset('/web/v1/libs/bower/marked') }}/marked.js" type="text/javascript"></script>
	
	<script>
	$(function(){
		$("#commonModal").on("hidden.bs.modal",function(){
			$(this).removeData("bs.modal"); 
		});
		$("#commonModal-lg").on("hidden.bs.modal",function(){
			$(this).removeData("bs.modal"); 
		});
		$("#checkAll").on('click',function(){
			if($(this).is(':checked')){
				$("input[name='ids[]']").prop("checked", true);
			}else{
				$("input[name='ids[]']").prop("checked", false);
			}
		});
		// 获取未读消息

		$(".common_msg_list").on('click',function(){
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.post("{{ route('/admin/notice/dashboard')}}",{pagesize:5},function(data){
				if(data.code == '1'){
					var msg_list = "";
					if(data.data.length == 0){
						msg_list += '<a href="javascript:void(0)" class="media-group-item">'+
							'<div class="media">'+
								'<div class="media-body">'+
									'<h5 class="media-heading">暂无消息'+
								'</div>'+
							'</div>'+
						'</a>';
					}else{
						for(var i = 0;i < data.data.length;i++){
							msg_list += '<a href="javascript:void(0)" class="media-group-item">'+
								'<div class="media">'+
									'<div class="media-body">'+
										'<h5 class="media-heading">'+data.data[i].level+" "+data.data[i].title+'</h5>'+
										'<small class="media-meta">'+data.data[i].time+'</small>'+
									'</div>'+
								'</div>'+
							'</a>';
						}
					}
					$("#common_msg_list").html(msg_list);
				}
			});
		});
	})
	</script>
	@yield('js')
</body>
</html>
