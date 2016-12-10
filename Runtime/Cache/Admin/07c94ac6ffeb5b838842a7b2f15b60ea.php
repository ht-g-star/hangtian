<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset=utf-8">
<title><?php echo C('WEB_SITE_TITLE');?>|跳转提示</title>
    <link rel="icon" type="image/png" href="/Public/static/amaze/i/favicon.png">
    <link rel="stylesheet" href="/Public/static/amaze/css/amazeui.css"/>
    <link rel="stylesheet" href="/Public/static/amaze/css/admin.css">
</head>
<body class="am-message">
<div class="am-g am-margin-top-lg">
    <div class="am-u-sm-12 am-u-sm-centered am-alert am-alert-warning">
        <div class="am-u-sm-1">&nbsp;</div>
        <div class="am-u-sm-2 am-vertical-align-middle am-vertical-align">
            <img src="/Public/static/amaze/i/icon_error.png" >
        </div>
        <div class="am-u-sm-9">
            <div class="am-u-xx-centered am-vertical-align " >
                <div class="am-vertical-align-middle"  >
                    <h1>抱歉,出错啦!</h1>
                    <p class=""><?php echo($error); ?></p>
                    <p class="detail"></p>
                    <p class="jump">
                        <b id="wait"><?php echo($waitSecond); ?></b> 秒后页面将自动跳转
                    </p>
                    <div>
                        <a id="href" href="<?php echo($jumpUrl); ?>">立即跳转</a>
                        <button id="btn-stop" type="button" onclick="stop()" class="am-btn am-btn-default am-margin-left-lg">停止跳转</button>
                        <a  class="am-margin-left-lg"   href="<?php echo(U('Public/logout')); ?>">重新登录</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-cf"></div>
    </div>
</div>
<script type="text/javascript">
(function(){
 var wait = document.getElementById('wait'),href = document.getElementById('href').href;
 var interval = setInterval(function(){
     	var time = --wait.innerHTML;
     	if(time <= 0) {
     		location.href = href;
     		clearInterval(interval);
     	};
     }, 1000);
  window.stop = function (){
         console.log(111);
            clearInterval(interval);
 }
 })();
</script>
</body>
</html>