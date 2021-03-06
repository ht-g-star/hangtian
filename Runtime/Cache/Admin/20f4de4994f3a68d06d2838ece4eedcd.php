<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>欢迎您登录<?php echo C('WEB_SITE_TITLE');?></title>

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport"
	      content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<link rel="alternate icon" type="image/png" href="/Public/static/amaze/i/favicon.png">
	<link rel="stylesheet" href="/Public/static/amaze/css/amazeui.css"/>
	<link rel="stylesheet" href="/Public/static/amaze/css/admin.css">
</head>
<head>
<body>
<div class="admin-container">
	<div class="am-g">
		<div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
			<div class="admin-login-logo">
				<img src="/Public/static/amaze/i/admin-login-logo.png">
			</div>
			<form action="<?php echo U('login');?>" method="post" class="am-form login-form">
				<h3>系统登录</h3>
				<h4>请填写您的登录信息</h4>
				<br>

				<div class="am-input-group">
					<span class="am-input-group-label"><i class="am-icon-user am-icon-fw"></i></span>
					<input type="text" class="am-form-field" name="username" placeholder="请填写用户名" autocomplete="off" required>
				</div>
				<br>

				<div class="am-input-group">
					<span class="am-input-group-label"><i class="am-icon-lock am-icon-fw"></i></span>
					<input type="password" class="am-form-field" name="password" placeholder="请填写密码" autocomplete="off" required>
				</div>
				<br>

				<div class="am-cf">

					<input type="submit" name="" value="登 录"
					       class="am-btn am-btn-primary am-btn-lg am-fl am-btn-block login-btn">

					<div class="check-tips"></div>

					<br>&nbsp;
					<br>
					<!--<a href="#" class="am-fr">忘记密码？</a>-->
				</div>
				<img src="/Public/static/amaze/i/login-wx.png" class="login-wx">
				<img src="/Public/static/amaze/i/login-rocket.png" class="login-rocket">
			</form>
			<p class="admin-login-footer">© 2015-2016年中国航天无锡疗养院版权所有</p>
		</div>
	</div>
</div>
<script src="/Public/static/amaze/js/jquery.min.js"></script>

<script>
	$(function () {
		$('html').css("height", "100%");
		$('body').css({"height":"100%","overflow":"hidden"});
	});

	/* 登陆表单获取焦点变色 */
	$(".login-form").on("focus", "input", function () {
		$(this).closest('.item').addClass('focus');
	}).on("blur", "input", function () {
		$(this).closest('.item').removeClass('focus');
	});


	$("form").submit(function () {
		var self = $(this);
		$.post(self.attr("action"), self.serialize(), success, "json");
		return false;

		function success(data) {
			if (data.status) {
				window.location.href = data.url;
			} else {
				self.find(".check-tips").text(data.info);
				//刷新验证码
				$(".reloadverify").click();
			}
		}
	});

	$(function () {
		//初始化选中用户名输入框
		$("#itemBox").find("input[name=username]").focus();
		//刷新验证码
		var verifyimg = $(".verifyimg").attr("src");
		$(".reloadverify").click(function () {
			if (verifyimg.indexOf('?') > 0) {
				$(".verifyimg").attr("src", verifyimg + '&random=' + Math.random());
			} else {
				$(".verifyimg").attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
			}
		});


	});
</script>
</body>
</html>