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
<!-- 头部 -->
<!--顶部菜单 -->
<header class="am-topbar am-topbar-fixed-top">
	<div class="am-container">
		<h1 class="am-topbar-brand  am-margin-top-sm">
			<a href="<?php echo U('Home/Index/index');?>"><img src="/Public/static/amaze/i/img_logo.png" title="<?php echo C('WEB_SITE_TITLE');?>"></a>
		</h1>
		<!---->
		<button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-secondary am-show-sm-only"
		        data-am-collapse="{target: '#collapse-head'}"><span class="am-sr-only">导航切换</span> <span
				class="am-icon-bars"></span>
		</button>
		<div class="am-collapse am-topbar-collapse" id="collapse-head">
			<ul class="am-nav am-nav-pills am-text-lg am-topbar-nav am-margin-left-lg">
				<li class='<?php if((CONTROLLER_NAME) == "Index"): ?>am-active<?php endif; ?> '>
					<a href="<?php echo U('Home/Index/index');?>">首页</a></li>
				<li class='<?php if((CONTROLLER_NAME) == "Suite"): ?>am-active<?php endif; ?> '>
					<a href="<?php echo U('Home/Suite/index');?>">健康体检</a></li>
				<li class='<?php if((CONTROLLER_NAME) == "Shop"): ?>am-active<?php endif; ?> <?php if((CONTROLLER_NAME) == "Article"): ?>am-active<?php endif; ?>'>
					<a href="<?php echo U('Home/Shop/index');?>">健康商城</a>
				</li>
				<li class='<?php if((CONTROLLER_NAME) == "Question"): ?>am-active<?php endif; ?> '>
					<a href="<?php echo U('Home/Question/index');?>">健康咨询</a></li>
				<li class='<?php if((CONTROLLER_NAME) == "Lesson"): ?>am-active<?php endif; ?> '>
					<a href="<?php echo U('Home/Lesson/index');?>">健康讲坛</a></li>
				<li class='<?php if((CONTROLLER_NAME) == "Doctor"): ?>am-active<?php endif; ?> '>
					<a href="<?php echo U('Home/Doctor/index');?>">名医堂</a></li>
				<li class='<?php if((CONTROLLER_NAME) == "Env"): ?>am-active<?php endif; ?> '>
					<a href="<?php echo U('Home/Env/index');?>">配套服务</a></li>
			</ul>
			<?php if(is_login()): ?><div class="am-topbar-right am-margin-top-lg">
					<a class="am-topbar-btn am-btn-sm" href="<?php echo U('Home/shopcart/index');?>"><span class="am-icon-shopping-cart"></span> 购物车</a>
				</div>
				<?php $login_user = $_SESSION['onethink_home']['user_auth']; ?>
				<div class="am-topbar-right am-margin-top-lg">
					欢迎你,<a href="<?php echo U('Home/Center/index');?>"><span class="am-text-primary"><?php echo ($login_user["realname"]); ?></span></a>|
					<a href="<?php echo U('Home/User/logout');?>">退出</a>
				</div>
				<?php else: ?>
				<div class="am-topbar-right am-margin-top-lg">
					<a class="am-topbar-btn am-btn-sm" href="<?php echo U('User/register');?>">注册</a>
				</div>
				<div class="am-topbar-right am-margin-top-lg">
					<a class="am-topbar-btn am-btn-sm" href="<?php echo U('User/login');?>">登录</a>
				</div><?php endif; ?>


		</div>
	</div>
</header>
<!-- /头部 -->


	<div class="am-g am-container am-margin-top-lg">
		<div class="breadcrumb-box">
			<ol class="am-breadcrumb am-breadcrumb-slash">
				<li><a href="<?php echo U('Home/Index/index');?>">首页</a></li>
				<li class="am-active">购物车</li>
			</ol>
		</div>
		<form action='<?php echo U("Pay/index");?>' method="post" name="myform" class="payform" target="_blank">
			<div class="shop-cart bg-f5 am-padding am-margin-bottom am-cf">
				<div class="am-u-sm-12 shop-cart-list am-padding-lg bg-fff">
					<div class="am-u-sm-6 am-padding">
						<img src="/Public/static/amaze/i/app-checkOk.png">

						<h2 class="am-inline-block am-padding-left">请您尽快支付付款完成订单！</h2>
					</div>
					<div class="am-u-sm-6 am-padding-top am-margin-top-xs am-text-right">应付总额：
						<?php $pay_info = $_SESSION['onethink_home']['pay_info']; ?>
						<span class="am-text-warning am-text-lg"><strong>¥<?php echo ($pay_info["money"]); ?>元</strong></span>
						<input type="hidden" name="money" id="money" class="form-control" value="<?php echo ($pay_info["money"]); ?>">

						<p>支付订单号： <?php echo ($codeid); ?><br/>
							<?php if($orderid1): echo ($orderid1); endif; ?>

							<input type="hidden" name="orderid" class="form-control" id="orderid" value="<?php echo ($codeid); ?>">
							<input type="hidden" name="orderid1" class="form-control" id="orderid1" value="<?php echo ($orderid1); ?>">
						</p>
					</div>

				</div>
				<div class="am-u-sm-12 bg-fff">
					<div class="am-u-sm-12 shop-cart-list am-padding">

						<?php if(($ordertype == 2) OR ($ordertype == 3)): ?><strong style="color:red;display:block;text-align:center;line-height:25px;">节假日期间的订单不予退订。非节假日的订单改期请至少提前4个工作日与我们联系。原则上，住宿、线路类订单只能改期，不能退订。</strong><?php endif; ?>

						选择以下支付方式付款
						<br><br>
						支付平台
						<small>大额支付推荐使用支付宝</small>
						<hr>
						<ul class="pay-select am-nav am-nav-pills bg-fff am-padding">
							<li>
								<label><input type="radio" name="paytype" value="alipay" checked=""/>
									支付宝<img src="/Public/static/amaze/i/img-alipay2.jpg"></label>
							</li>
							<li>
								<label><input type="radio" name="paytype" value="balance" checked=""/>
									余额支付<img src="/Public/static/amaze/i/icon-yue.png"></label>
							</li>
							<!--<li>-->
							<!--<label><input type="radio" name="paytype" value="tenpay"/>-->
							<!--财付通<img src="/Public/static/amaze/i/img-cft.jpg"></label>-->
							<!--</li>-->
							<!--<li>-->
							<!--<label><input type="radio" name="paytype" value="unionpay"/>-->
							<!--银联<img src="/Public/Home/images/union.png"></label>-->
							<!--</li>-->
							<!--<li>-->
							<!--<label><input type="radio" name="paytype" value="palpay"/>-->
							<!--贝宝<img src="/Public/Home/images/palpay.png"></label>-->
							<!--</li>-->
							<!--<li>-->
							<!--<label><input type="radio" name="paytype" value="yeepay"/>-->
							<!--易付宝<img src="/Public/Home/images/yeepay.png"></label>-->
							<!--</li>-->
							<!--<li>-->
							<!--<label><input type="radio" name="paytype" value="kuaiqian"/>-->
							<!--快钱<img src="/Public/Home/images/kuaiqian.png"></label>-->
							<!--</li>-->
						</ul>
						<hr>
						<div class="submit am-u-sm-4 am-u-sm-centered am-margin-top">
							<a class="btn_submit am-btn am-btn-primary am-btn-lg am-btn-block" title="提交" href="javascript:void(0);"
							   onclick="submitpay();return false"/>提交</a>
						</div>

					</div>
				</div>
			</div>
		</form>
	</div>

	<div class="am-modal am-modal-confirm" tabindex="-1" id="pay_dialog">
		<!-- Modal 对话框内容 -->
		<div class="am-modal-dialog">

			<div class="am-modal-hd">
				<h3>支付页面</h3>
			</div>
			<div class="am-modal-bd">
				<div class="form_loading">
					<img src="/Public/Home/images/loading.gif">正在进行支付，请勿关闭！<span id="times"></span>
				</div>
			</div>
			<div class="am-modal-footer">
				<a class="am-modal-btn" href="<?php echo U('Center/index');?>" data-am-modal-confirm id="btn_pay_ok">支付成功</a>
				<a class="am-modal-btn" href="<?php echo U('Center/index');?>" data-am-modal-cancel id="btn_pay_fail">支付失败</a>
			</div>
		</div>
		<!-- Modal 对话框内容 -->
	</div>

	<div class="am-modal " tabindex="-1" id="balance_pay">
		<!-- Modal 对话框内容 -->
		<div class="am-modal-dialog">

			<div class="am-modal-hd">
				<h3>余额支付</h3>
			</div>
			<hr class="am-margin-0">
			<div class="am-modal-bd">
				您的余额为: <span id="balance_value" class="am-text-warning am-text-xl"><?php echo (round($balance)); ?></span><br><br>
				输入密码: <input name="password" id="password" type="password" value="" class="am-padding-xs"/><br>
				<button id="balance_pay_btn" class="am-btn am-btn-primary am-padding-left-xl am-padding-right-xl am-margin-top-xl" type="button">支付</button>
				<button class="am-btn am-btn-success am-margin-top-xl" data-am-modal-close>关闭</button>
			</div>
			<div class="am-modal-footer">
			</div>
		</div>
		<!-- Modal 对话框内容 -->
	</div>

	<!-- 对话框 结束-->

	<script type="text/javascript">
		function submitpay() {

			if ($("input[name='paytype']:checked").val() == 'balance') {
				$("#balance_pay").modal({
					relatedTarget: this,
					closeViaDimmer: 0
				});
			} else {
				$("#pay_dialog").modal({
					relatedTarget: this,
					closeViaDimmer: 0,
					onConfirm: function () {
						location.href = $("#btn_pay_ok").attr("href");
					},
					// closeOnConfirm: false,
					onCancel: function () {
						location.href = $("#btn_pay_fail").attr("href");

					}
				});
				document.myform.submit();
			}
		}
		$("#balance_pay_btn").click(function () {
			$(this).attr("disabled","disabled");
			var balance = parseFloat($.trim($("#balance_value").text()));
			var cost = parseFloat($("#money").val());
			if (!balance || balance < cost) {
				topAlert("余额不足!");
				$(this).attr("disabled",false);
				return;
			}
			if (confirm('即将扣除您此次订单的金额<?php echo ($pay_info["money"]); ?>元,是否确认?')) {
				$.post("<?php echo U('Pay/balance');?>", {
					"orderid": $("#orderid").val(),
					"orderid1": $("#orderid1").val(),
					'password':$("#password").val()
				}, function (data) {
					for(i in data){
						alert(i+"#");
						alert(data[i]);
					}
					
					if (data) {
						topAlert(data.info, data.status);
						setTimeout(function () {
							location.href = data.url;
						}, 2000);
					} else {
						topAlert("连接出错,请刷新重试,或联系管理员!");
						$(this).attr("disabled",false);
						return;
					}
				}, 'json')
			}
		});
	</script>

<!-- 购物车js -->
<script>
//	//购物车显示与隐藏
//	var Shopcart = document.getElementById('shopping_cart');
//	var Goodsmenu = document.getElementById('goods');
//	var timerr = null;//定义定时器变量
//	//鼠标移入div1或div2都把定时器关闭了，不让他消失
//	Shopcart.onmouseover = Goodsmenu.onmouseover = function () {
//		Goodsmenu.style.display = 'block';
//		clearTimeout(timerr);
//	}
//	//鼠标移出div1或div2都重新开定时器，让他延时消失
//	Shopcart.onmouseout = Goodsmenu.onmouseout = function () {
//		//开定时器
//		timerr = setTimeout(function () {
//			Goodsmenu.style.display = 'none';
//		}, 10);
//	}
//
//	//购物车动态删除
//	function delcommon(event) { //获取事件源
//		event = event ? event : window.event;
//		var obj = event.srcElement ? event.srcElement : event.target;
////这时obj就是触发事件的对象，可以使用它的各个属性
////还可以将obj转换成jquery对象，方便选用其他元素
//		str = obj.innerHTML.replace(/<\/?[^>]*>/g, ''); //去除HTML tag
//
//		var $obj = $(obj);
//		var str = $obj.parent().prev().html();
//		if (obj.rel == "del") {
//			var str = obj.name;
//			var uexist = "<?php echo get_username();?>";
//			//全选的实现 定义删的发送路径
//			if (uexist) {
//				var del = '<?php echo U("Shopcart/delItemByuid");?>';
//			}
//			else {
//				var del = '<?php echo U("Shopcart/delItem");?>';
//
//			}
//
//			$.ajax({
//				type: 'post', //传送的方式,get/post
//				url: del, //发送数据的地址
//				data: {sort: str},
//				dataType: "json",
//				success: function (data) {
//					var $obj = $(obj);
//					$obj.parents("li").remove();
//					if (data.sum == "0") {  //判断购物车数量是否为0，为0则隐藏底部查看工具
//						$("div.sc_goods_foot").hide();
//						var html = '<p class="sc_goods_none" >您的购物车还是空的，赶紧行动吧！</p>';
//						$("ul.sc_goods_ul").html(html);
//
//
//					}
//					else {
//						$("div.sc_goods_foot").show();
//					}
//
//				},
//				error: function (event, XMLHttpRequest, ajaxOptions, thrownError) {
//					alert(XMLHttpRequest + thrownError);
//				}
//			})
//		}
//
//	}
//	//购物车删除结束


</script>
<!-- 购物车js -->
<!-- 底部 -->
<footer class="footer">
	<div class="am-g am-container am-padding-xl">
		<div class="col-sm-2 am-u-lg-2">
			<p>关于我们</p>
			<ul>
				<li><a href="<?php echo U('Home/Article/detail',array('id'=>121));?>">738疗养院最新动态</a></li>
				<li><a href="<?php echo U('Home/Article/detail',array('id'=>122));?>">诚聘英才</a></li>
				<li><a href="<?php echo U('Home/Env/index');?>">配套服务</a></li>
				<li><a href="./index.php?s=/home/article/detail/id/250.html">一卡通使用说明</a></li>
			</ul>
		</div>
		<div class="col-sm-2 am-u-lg-2">
			<p>网站条款</p>
			<ul>
				<li><a href="<?php echo U('Home/Article/detail',array('id'=>123));?>">版权声明</a></li>
				<li><a href="<?php echo U('Home/Article/detail',array('id'=>124));?>">免责声明</a></li>
			</ul>
		</div>
		<div class="col-sm-2 am-u-lg-2">
			<p>购物保障</p>
			<ul>
				<li><a href="<?php echo U('Home/Article/detail',array('id'=>125));?>">发票保障</a></li>
				<li><a href="<?php echo U('Home/Article/detail',array('id'=>132));?>">售后服务</a></li>
				<li><a href="<?php echo U('Home/Article/detail',array('id'=>131));?>">退换货操作指南</a></li>
				<li><a href="<?php echo U('Home/Article/detail',array('id'=>126));?>">退款方式和时效</a></li>
			</ul>
		</div>
		<div class="col-sm-2 am-u-lg-2">
			<p>售后服务</p>
			<ul>
				<li><a href="<?php echo U('Home/Article/detail',array('id'=>127));?>">联系客服</a></li>
				<li><a href="<?php echo U('Home/Article/detail',array('id'=>131));?>">帮助中心</a></li>
				<!-- <li><a href="<?php echo U('Home/Article/detail',array('id'=>132));?>">售后政策</a></li> -->
			</ul>
		</div>
		<div class="col-sm-4 am-u-lg-4 footer-border">
			<div class="am-g">
				<div class="am-u-lg-5"><img src="/Public/static/amaze/i/img_twocode.jpg"></div>
				<div class="am-u-lg-7">扫描二维码关注我们<br>
					或搜索公众号：htwxjkgl<br>
					<span class="footer_line"></span>
					<span class="hot_line">服务热线</span>
					<img src="/Public/static/amaze/i/img_contact_tel.png">
				</div>
			</div>
		</div>
	</div>
	<div id="bottom" class="am-g am-text-center footer_info am-padding">
		版权所有：中国航天科技集团公司七三八疗养院<br>
		预订、咨询电话：0510-85555738 传真：0510—85559006 电子邮箱：ht738@126.com<br>
		地址：无锡市大浮西渚头1号　邮编：214081<br>
		<a href="http://www.miitbeian.gov.cn"><?php echo C('WEB_SITE_ICP');?>号</a>

		<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1260107831'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/z_stat.php%3Fid%3D1260107831%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script>

	</div>
	<!--<div class="am-toolbar" id="am-toolbar" style="right: 355.5px;">-->
	<!--<a href="/#top" title="回到顶部" class="am-icon-btn am-icon-arrow-up am-active" id="amz-go-top"></a>-->
	<!--<a href="#" title="常见问题" class="am-icon-faq am-icon-btn am-icon-question-circle"></a>-->
	<!--</div>-->

	<div class="right_bar">
		<div data-am-widget="gotop" class="am-gotop am-gotop-fixed am-no-layout am-active">
			<a href="#" title=""><img class="am-gotop-icon-custom" src="/Public/static/amaze/i/icon_index_gotop.png"></a>
		</div>
		<a id="tel_btn" href="#bottom"  title=""><img  class="am-gotop-icon-custom"  src="/Public/static/amaze/i/icon_index_tel.png"></a>
		<a href="tencent://message/?uin=<?php echo C('QQ');?>&Site=&Menu=yes" title=""><img class="am-gotop-icon-custom" src="/Public/static/amaze/i/icon_index_qq.png"></a>
		<a href="#wechat" data-am-modal="wechat"><img class="am-gotop-icon-custom" src="/Public/static/amaze/i/icon_index_wechat.png"></a>
		<a href="http://weibo.com/ht738" target="_blank" title=""><img class="am-gotop-icon-custom" src="/Public/static/amaze/i/icon_index_weibo.png"></a>
	</div>
	<div class="am-modal am-modal-no-btn" tabindex="-1" id="wechat">
		<div class="am-modal-dialog">
			<div class="am-modal-hd">请扫码二维码关注我们
				<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
			</div>
			<div class="am-modal-bd">
				<div class="am-u-sm-12"><img src="/Public/static/amaze/i/img_twocode.jpg" height="200px"></div>
				<div class="am-cf"></div>
			</div>
		</div>
	</div>
</footer>
<!-- /底部 -->
<div class="hidden am-hide"><!-- 用于加载统计代码等隐藏元素 -->
	<script type="text/javascript">//var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1257408010'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/z_stat.php%3Fid%3D1257408010' type='text/javascript'%3E%3C/script%3E"));</script>
	

	
</div>

</body>
</html>