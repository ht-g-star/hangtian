<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title><?php echo C('WEB_SITE_TITLE');?>|跳转提示</title>
	<style type="text/css">
		* {
			padding: 0;
			margin: 0;
		}

		body {
			background: #f5f5f5;
			font-family: '微软雅黑';
			color: #333;
			font-size: 16px;
		}

		.system-message {
			padding: 48px;
			border: 1px solid #ddd;
			border-radius: 10px;
			margin: 0 auto;
			background: #fff;
			width: 800px;
			margin-top: 100px;
			text-align: center
		}

		.system-message h1 {
			font-size: 40px;
			font-weight: normal;
			line-height: 80px;
			margin-bottom: 12px;
			color: #dd122a
		}

		.system-message .jump {
			padding-top: 10px;
			margin-bottom: 20px
		}

		.system-message .jump a {
			color: #333;
		}

		.system-message .success, .system-message .error {
			line-height: 1.8em;
			font-size: 20px
		}

		.system-message .detail {
			font-size: 12px;
			line-height: 20px;
			margin-top: 12px;
			display: none
		}

		#wait {
			font-size: 46px;
		}

		#btn-stop, #href {
			display: inline-block;
			margin-right: 10px;
			font-size: 16px;
			line-height: 18px;
			text-align: center;
			vertical-align: middle;
			cursor: pointer;
			border: 0 none;
			background-color: #dd122a;
			padding: 10px 20px;
			color: #fff;
			font-weight: bold;
			border-color: transparent;
			text-decoration: none;
		}

		#btn-stop:hover, #href:hover {
			background-color: #c61328;
		}

		.spinner {
			margin: 10px auto;
			width: 40px;
			height: 40px;
			position: relative;
		}

		.container1 > div, .container2 > div, .container3 > div {
			width: 12px;
			height: 12px;
			background-color: #333;

			border-radius: 100%;
			position: absolute;
			-webkit-animation: bouncedelay 1.2s infinite ease-in-out;
			animation: bouncedelay 1.2s infinite ease-in-out;
			-webkit-animation-fill-mode: both;
			animation-fill-mode: both;
		}

		.spinner .spinner-container {
			position: absolute;
			width: 100%;
			height: 100%;
		}

		.container2 {
			-webkit-transform: rotateZ(45deg);
			transform: rotateZ(45deg);
		}

		.container3 {
			-webkit-transform: rotateZ(90deg);
			transform: rotateZ(90deg);
		}

		.circle1 {
			top: 0;
			left: 0;
		}

		.circle2 {
			top: 0;
			right: 0;
		}

		.circle3 {
			right: 0;
			bottom: 0;
		}

		.circle4 {
			left: 0;
			bottom: 0;
		}

		.container2 .circle1 {
			-webkit-animation-delay: -1.1s;
			animation-delay: -1.1s;
		}

		.container3 .circle1 {
			-webkit-animation-delay: -1.0s;
			animation-delay: -1.0s;
		}

		.container1 .circle2 {
			-webkit-animation-delay: -0.9s;
			animation-delay: -0.9s;
		}

		.container2 .circle2 {
			-webkit-animation-delay: -0.8s;
			animation-delay: -0.8s;
		}

		.container3 .circle2 {
			-webkit-animation-delay: -0.7s;
			animation-delay: -0.7s;
		}

		.container1 .circle3 {
			-webkit-animation-delay: -0.6s;
			animation-delay: -0.6s;
		}

		.container2 .circle3 {
			-webkit-animation-delay: -0.5s;
			animation-delay: -0.5s;
		}

		.container3 .circle3 {
			-webkit-animation-delay: -0.4s;
			animation-delay: -0.4s;
		}

		.container1 .circle4 {
			-webkit-animation-delay: -0.3s;
			animation-delay: -0.3s;
		}

		.container2 .circle4 {
			-webkit-animation-delay: -0.2s;
			animation-delay: -0.2s;
		}

		.container3 .circle4 {
			-webkit-animation-delay: -0.1s;
			animation-delay: -0.1s;
		}

		@-webkit-keyframes bouncedelay {
			0%, 80%, 100% {
				-webkit-transform: scale(0.0)
			}
			40% {
				-webkit-transform: scale(1.0)
			}
		}

		@keyframes bouncedelay {
			0%, 80%, 100% {
				transform: scale(0.0);
				-webkit-transform: scale(0.0);
			}
			40% {
				transform: scale(1.0);
				-webkit-transform: scale(1.0);
			}
		}
	</style>
</head>
<body>
<div class="system-message">
	<div class="spinner">
		<div class="spinner-container container1">
			<div class="circle1"></div>
			<div class="circle2"></div>
			<div class="circle3"></div>
			<div class="circle4"></div>
		</div>
		<div class="spinner-container container2">
			<div class="circle1"></div>
			<div class="circle2"></div>
			<div class="circle3"></div>
			<div class="circle4"></div>
		</div>
		<div class="spinner-container container3">
			<div class="circle1"></div>
			<div class="circle2"></div>
			<div class="circle3"></div>
			<div class="circle4"></div>
		</div>
	</div>
	<h1>抱歉,出错啦!</h1>

	<p class="error"><?php echo($error); ?></p>

	<p class="detail"></p>

	<p class="jump">
		<b id="wait"><?php echo($waitSecond); ?></b> 秒后页面将自动跳转
	</p>

	<div>
		<a id="href" id="btn-now" href="<?php echo($jumpUrl); ?>">立即跳转</a>
		<button id="btn-stop" type="button" onclick="stop()">停止跳转</button>
		<a href="<?php echo(U('Public/logout')); ?>">重新登录</a>
	</div>
</div>
<script type="text/javascript">
	(function () {
		var wait = document.getElementById('wait'), href = document.getElementById('href').href;
		var interval = setInterval(function () {
			var time = --wait.innerHTML;
			if (time <= 0) {
				location.href = href;
				clearInterval(interval);
			}
			;
		}, 1000);
		window.stop = function () {
			console.log(111);
			clearInterval(interval);
		}
	})();
</script>
</body>
</html>