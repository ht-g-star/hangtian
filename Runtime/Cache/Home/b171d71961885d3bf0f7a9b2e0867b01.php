<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class="no-js">
<head lang="en">
	<meta charset="UTF-8">
	<title><?php echo ($meta_title); ?>_<?php echo C('WEB_SITE_TITLE');?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport"
	      content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<!--<link href="/Public/Home/css/top.css" rel="stylesheet">-->
	<!--<link href="/Public/Home/css/common.css" rel="stylesheet">-->
	<!--<link href="/Public/Home/css/footer.css" rel="stylesheet">-->
	<link rel="stylesheet" href="/Public/static/amaze/css/amazeui.min.css"/>
	<link rel="stylesheet" href="/Public/static/amaze/css/common.css"/>
	<link rel="alternate icon" type="image/png" href="/Public/static/amaze/i/favicon.png">
	<!--[if lt IE 9]>
	<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
	<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
	<script src="/Public/static/amaze/js/amazeui.ie8polyfill.min.js"></script>
	<![endif]-->
	<!--[if (gte IE 9)|!(IE)]><!-->
	<script type="text/javascript" src="/Public/Home/js/jquery.min.js"></script>
	<!--<![endif]-->
	<script type="text/javascript" src="/Public/static/amaze/js/amazeui.min.js"></script>
	<script type="text/javascript">
	(function(){
		var ThinkPHP = window.Think = {
			"ROOT"   : "", //当前网站地址
			"APP"    : "/index.php?s=", //当前项目地址
			"PUBLIC" : "/Public", //项目公共目录地址
			"DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
			"MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
			"VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
		}
	})();
</script>
	<script src="/Public/static/js/jquery.enplaceholder.js"></script>
	<script type="text/javascript" src="/Public/static/think.js"></script>
	<script type="text/javascript" src="/Public/Home/js/public.js"></script>
	
</head>
<body>

	<form class="form-horizontal login-user" action='<?php echo U("User/login");?>' name="user" method="post">
		<div class="am-g login-container">
			<div class="login-box am-u-sm-centered">
				<div class="am-u-md-11 am-u-sm-centered am-padding-top-xl am-padding-bottom-xl">
					<a href="<?php echo U('Home/Index/index');?>"><img src="/Public/static/amaze/i/img_logo.png"></a>

					<h3>用户登录</h3>

					<div class="am-input-group">
						<span class="am-input-group-label"><i class="am-icon-user am-icon-fw"></i></span>
						<input type="text" id="username" name="username" class="am-form-field" placeholder="身份证号/会员卡号" required>
					</div>
					<br>

					<div class="am-input-group">
						<span class="am-input-group-label"><i class="am-icon-lock am-icon-fw"></i></span>
						<input type="password" id="password" name="password" class="am-form-field" placeholder="密码" required>
					</div>
					<br>

					<div class="am-input-group">
						<input name="reffer" type="hidden" value="<?php echo ($referr); ?>">
						<input type="checkbox" name="remember"> 自动登录
					</div>

					<br>

					<div class="am-cf">
						<input type="submit" name="" id="login_btn_cart" value="登 录" class="am-btn am-btn-primary am-btn-lg am-fl am-btn-block">
						<br>&nbsp;
						<br>
						<a href="<?php echo U('User/register');?>" class="am-fr">注册</a>
						<a href="<?php echo U('User/forget');?>" class="am-fl">忘记密码</a>
					</div>
					<br>

				</div>
			</div>
			<p class="am-text-center am-text-sm">©2016年中国航天无锡疗养院版权所有</p>
		</div>
	</form>


<!-- 底部 -->
<!-- /底部 -->
<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
	
</div>

	<script type="text/javascript">

		$(document)
				.ajaxStart(function () {
					$("button:submit").addClass("log-in").attr("disabled", true);
				})
				.ajaxStop(function () {
					$("button:submit").removeClass("log-in").attr("disabled", false);
				});


		$("#loginform").submit(function () {
			var self = $(this);
			$.post(self.attr("action"), self.serialize(), success, "json");
			return false;

			function success(data) {
				if (data.status) {
					window.location.href = data.url;
				} else {
					self.find(".Validform_checktip").text(data.info);
					//刷新验证码
					$(".reloadverify").click();
				}
			}
		});

		$(function () {
			var verifyimg = $(".verifyimg").attr("src");
			$(".reloadverify").click(function () {
				if (verifyimg.indexOf('?') > 0) {
					$(".verifyimg").attr("src", verifyimg + '&random=' + Math.random());
				} else {
					$(".verifyimg").attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
				}
			});
		});
		$(function () {
			$("div.login-container").height($(window).height());
		});
	</script>

</body>
</html>