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
	
	<link rel="stylesheet" href="/Public/Home/css/order.css">

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
				<li class="am-active">优惠券</li>
			</ol>
		</div>
		<div class="userCenter am-cf ">
			<div class="am-u-sm-3">
				<!-- 左侧菜单 -->
				<ul class="am-nav am-padding-xl">
	<li class="am-nav-header"><h3><a class="home-me">订单中心</a></h3>
		<ul class="am-nav">

			<li><a href='<?php echo U("Center/allorder");?>'>普通订单</a></li>
			<li><a href='<?php echo U("Jipiao/orderList");?>'>机票订单</a></li>
			<li><a href='<?php echo U("Hotel/allorder");?>'>酒店订单</a></li>
			<li><a href='<?php echo U("Line/allorder");?>'>旅游订单</a></li>
			<li><a href='<?php echo U("SuiteOrder/allorder");?>'>体检预约</a></li>
			<li><a href='<?php echo U("UserCoupon/index");?>'>优 惠 券</a></li>
			<!--<li><a href='<?php echo U("Comment/lists");?>'>评价管理</a></li>-->
		</ul>
	</li>
	<li class="am-nav-header"><h3><a class="home-me">个人中心</a></h3>
		<ul class="am-nav">
			<li><a href='<?php echo U("Center/index");?>'>我的主页</a></li>
			<li><a href='<?php echo U("Member/index");?>'>个人资料</a></li>
			<li><a href='<?php echo U("Report/index");?>'>报告历史</a></li>
			<li><a href='<?php echo U("Address/index");?>'>收货地址</a></li>
			<li><a href='<?php echo U("User/profile");?>'>修改密码</a></li>
			<!--<li><a href='<?php echo U("Account/security");?>'>安全中心</a></li>-->
			<!--<li><a href='<?php echo U("Shopcart/index");?>'>我的购物车</a></li>-->
			<!--<li><a href='<?php echo U("UserEnvelope/index");?>'>站内信</a></li>-->
			<li><a href='<?php echo U("Collect/index");?>'>我的收藏</a></li>
		</ul>
	</li>
</ul>

<script>/* 左边菜单高亮 */
url = window.location.pathname + window.location.search;
url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
$("a[href='" + url + "']").addClass("am-active");
</script>
   
				<!-- 左侧菜单 -->
			</div>
			<div id="memberCenter" class="am-u-sm-9 am-padding-xl userCenter-r"><!-- 个人中心 初始状态 start -->
				<div class=" " id="MemberCenterCtrl">
					<div class="am-cf">
						<div class="am-u-sm-6"><h2 class="am-fl">我的优惠券</h2></div>
					</div>
					<div class="am-g">
						<?php if(empty($couponlist)): ?><div class="personal_info_none_goods am-u-sm-12">
								<p>暂时没有优惠券</p>
							</div>
							<?php else: ?>
							<ul class="userhas am-list" id="cuser">
								<?php if(is_array($couponlist)): $i = 0; $__LIST__ = $couponlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
										<a href="javascript:void(0);"><img src="<?php echo (get_cover($vo["icon"],'path')); ?>" width="170" height="170"/></a></p>
										<p>
											<a href="javascript:void(0);"><span class="red"><?php echo ($vo["title"]); ?></span></a>
										</p>

										<p class="am-text-xs">价值：<span class="red"><?php echo ($vo["price"]); ?>元</span>|最低消费:<?php echo ($vo["lowpayment"]); ?>
										</p>
										<p class="am-text-xs">有效期：<span class="red"><?php echo (time_format($vo["start_time"],'Y/m/d')); ?>-<?php echo (time_format($vo["end_time"],'Y/m/d')); ?></span>
										</p>
										<p class="am-text-xs"><?php echo (mb_substr(strip_tags((isset($vo["description"]) && ($vo["description"] !== ""))?($vo["description"]):'&nbsp;'),0,20,'utf8')); ?></p>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>
							</ul><?php endif; ?>
					</div>
					<hr>
					<div class="am-g am-hide">
						<span class="red">已使用</span>
						<ul class="userhas am-list" id="cuser2">
							<?php if(is_array($couponusedlist)): $i = 0; $__LIST__ = $couponusedlist;if( count($__LIST__)==0 ) : echo "暂无" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
									<a href="javascript:void(0);"><img src="<?php echo (get_cover($vo["icon"],'path')); ?>" width="170" height="170"/></a></p>
									<p>
										<a href="javascript:void(0);"><span class="red"><?php echo ($vo["title"]); ?></span></a>
									</p>

									<p class="am-text-xs">价值：<span class="red"><?php echo ($vo["price"]); ?>元</span>|最低消费:<?php echo ($vo["lowpayment"]); ?>
									</p>
									<p class="am-text-xs">有效期：<span class="red"><?php echo (time_format($vo["start_time"])); ?>-<?php echo (time_format($vo["end_time"])); ?></span>
									</p>
									<p class="am-text-xs"><?php echo (mb_substr(strip_tags($vo["description"]),0,20,'utf8')); ?></p>
								</li><?php endforeach; endif; else: echo "暂无" ;endif; ?>
						</ul>
					</div>
					<script type="text/javascript">

						function getcoupon(event) {
							event = event ? event : window.event;
							var obj = event.srcElement ? event.srcElement : event.target;
//这时obj就是触发事件的对象，可以使用它的各个属性
//还可以将obj转换成jquery对象，方便选用其他元素
							str = obj.innerHTML.replace(/<\/?[^>]*>/g, ''); //去除HTML tag

							var $obj = $(obj);
							var id = obj.rel;
							var b = '<?php echo U("UserCoupon/get");?>';
							$.ajax({
								type: "post",
								url: '<?php echo U("User/getcoupon");?>',
								data: {
									couponid: id
								},
								dataType: "json",
								success: function (data) {
									alert(data.msg);
									if (data.status == "1") {
										window.location.reload();
									}
								}
							});
						}
					</script>
					<!-- 个人中心 初始状态 end --></div>
			</div>
		</div>
	</div>

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
	<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1257408010'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/z_stat.php%3Fid%3D1257408010' type='text/javascript'%3E%3C/script%3E"));</script>
	

	
</div>

</body>
</html>