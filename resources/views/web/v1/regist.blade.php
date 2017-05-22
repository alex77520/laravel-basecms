<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
	<title>微蚁儿 - 用户登录</title>
	<meta name="description" content="微信小程序,后台管理,微蚁儿" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ URL::asset('favicon.ico') }}" rel="Shortcut Icon">
	<link rel="stylesheet" href="{{ URL::asset('/web/v1/libs/bower/font-awesome/css') }}/font-awesome.min.css">
	<link rel="stylesheet" href="{{ URL::asset('/web/v1/libs/bower/material-design-iconic-font/dist/css') }}/material-design-iconic-font.css">
	<link rel="stylesheet" href="{{ URL::asset('/web/v1/libs/bower/animate.css') }}/animate.min.css">
	<link rel="stylesheet" href="{{ URL::asset('/web/v1/assets/css') }}/bootstrap.css">
	<link rel="stylesheet" href="{{ URL::asset('/web/v1/assets/css') }}/app.css">
	<link rel="stylesheet" href="{{ URL::asset('/web/plugins/formvalidator') }}/bootvalid.css">
	<link rel="stylesheet" href="{{ URL::asset('/web/plugins/jquery-confirm') }}/jquery.confirm.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="simple-page">
	<div class="simple-page-wrap">
		<div class="simple-page-logo animated swing">
			<a href="{{ url('/') }}">
				{{-- <span>Veeyer</span> --}}
			</a>
		</div><!-- logo -->
		<div class="simple-page-form animated flipInY">
            <h4 class="form-title m-b-xl text-center">账户注册</h4>
            <form action="{{ route('/regist') }}" method="post" id="login-form">
                {{ csrf_field() }}
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="用户名" name="username">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="邮箱" name="email">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="密码" name="password">
                </div>
                <input type="submit" class="btn btn-primary" value="注 册">
            </form>
        </div>
        <div class="simple-page-footer">
            <p>
                <small>已经有账号 ?</small>
                <a href="/login">去登录</a>
            </p>
        </div>
	</div>
    <script src="{{ URL::asset('/web/v1/libs/bower/jquery/dist/') }}/jquery.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/v1/libs/bower/bootstrap-sass/assets/javascripts') }}/bootstrap.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/plugins/formvalidator') }}/bootvalid.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/web/plugins/jquery-confirm') }}/jquery.confirm.js" type="text/javascript"></script>
    <script>
        $(function(){
            // 校验是否有自动登录
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#login-form').bootstrapValidator({
                message: '填写的数据不合法',
                feedbackIcons: {
                    valid: 'fa fa-ok',
                    invalid: 'fa fa-remove',
                    validating: 'fa fa-refresh'
                },
                fields: {
                    username: {
                        message: '用户名不合法',
                        validators: {
                            notEmpty: {
                                message: '用户名不能为空'
                            },
                            stringLength: {
                                min: 3,
                                max: 10,
                                message: '用户名限制3-10位',
                            },
                        }
                    },
                    email: {
                        message: '邮箱不合法',
                        validators: {
                            notEmpty: {
                                message: '邮箱不合法'
                            },
                            emailAddress: {
                                message: '请输入正确的邮件地址如：123@qq.com'
                            }
                        }
                    },
                    password: {
                        message: '密码不合法',
                        validators: {
                            notEmpty: {
                                message: '密码不能为空'
                            },
                            stringLength: {
                                min: 8,
                                max: 20,
                                message: '密码为8-20位',
                            },
                        }
                    }
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();
                var $form = $(e.target);
                var bv = $form.data('bootstrapValidator');
                $.ajax({
                    url: $form.attr('action'),
                    type: "post",
                    dataType: "json",
                    data: $form.serialize(),
                    success: function (data) {
                        if(data.code == '1'){
                            @if(!Session::has('login_return'))
                                window.location.href='{{ route("/admin/") }}';
                            @else
                                window.location.href='{{ Session::get("login_return") }}';
                            @endif
                        }else{
                            $.alert(data.data);
                        }
                    },
                    error: function (data) {
                        var errors = $.parseJSON(data.responseText);
                        $.each(errors, function (key, value) {
                            $("small[data-bv-for='"+key+"']").parent().addClass('has-error');
                            $("small[data-bv-for='"+key+"'][data-bv-validator='notEmpty']").html(value);
                            $("small[data-bv-for='"+key+"'][data-bv-validator='notEmpty']").show();
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>