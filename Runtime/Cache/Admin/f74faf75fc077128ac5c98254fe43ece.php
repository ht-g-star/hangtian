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
    <div class="am-u-sm-10 am-u-sm-centered am-alert am-alert-success">
        <div class="am-u-sm-3">&nbsp;</div>
        <div class="am-u-sm-2">
            <img src="/Public/static/amaze/i/icon_success.png" >
        </div>
        <div class="am-u-sm-5">
        <div class="am-u-xx-centered am-vertical-align " style="height:320px;">
            <div class="am-vertical-align-middle ">
                <h3>恭喜您!</h3>
                    <h1 class="success"><?php echo($message); ?></h1>
                    <p class="detail"></p>
                    <p class="jump">
                    <b id="wait"><?php echo($waitSecond); ?></b> 秒后页面将自动跳转
                </p>
                <div>
                    <a id="href" class="am-text-default" id="btn-now" href="<?php echo($jumpUrl); ?>">立即跳转</a>
                    <button id="btn-stop" type="button" onclick="stop()" class="am-btn am-btn-default am-margin-left-lg">停止跳转</button>
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