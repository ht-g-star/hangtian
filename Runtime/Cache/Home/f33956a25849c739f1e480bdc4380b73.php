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
	<style>
		a.trash{
			background-color:transparent;
		}
	</style>

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
				<li class="am-active">个人中心</li>
			</ol>
		</div>
		<div class="userCenter am-cf">
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
			<div class="center_right" style="display: none;">
				<div class="center_right_loading"></div>
			</div>
			<div id="memberCenter" class="userCenter-r am-u-sm-9 am-padding-xl">
				<!-- 个人中心 初始状态 start -->
				<div class="am-g" id="MemberCenterCtrl">
					<!--标题-->
					<div class="am-cf">
						<div class="am-u-sm-6"><h2 class="am-fl">普通订单</h2></div>
					</div>
					<!--标题-->
					<div class="am-tabs am-margin-left">
						<ul class="am-tabs-nav am-nav am-nav-tabs">
							<li class="am-active"><a href='<?php echo U("Center/allorder");?>' class="red">所有订单</a></li>
							<li><a href='<?php echo U("Center/needpay");?>' class="red">待支付订单</a></li>
							<li><a href='<?php echo U("Center/tobeshipped");?>' class="red">待发货订单</a></li>
							<li><a href='<?php echo U("Center/tobeconfirmed");?>' class="red">待确认订单</a></li>
						</ul>
					</div>
					<p class="am-u-sm-12 am-text-xs am-text-warning">未支付的订单30分钟后会被取消.</p>
					<br/>
					<?php if(empty($list)): ?><p class="am-text-center am-padding-xl">
							没有订单，<a href='<?php echo U("shop/index");?>' class="am-text-warning">快去下单吧</a></p>
						<?php else: ?>
						<form action='<?php echo U("order/del");?>' method="post" name="delform">
							<div class="order_del am-padding-top am-padding-bottom am-hide">
								<!--<span>-->
									<!--<input class="checkbox check-all" type="checkbox"> 全选 <a href='javascript:void(0)' onclick="delorder()">删除选中的订单</a> </span>-->
							</div>
							<div class="good-canshu am-margin-top">
								<span class="good-name am-text-left am-padding-left">商品名称</span><span class="good-price">售价</span><span class="good-num">数量</span>
								<span class="good-action">商品操作</span><span class="good-total">总金额(元)</span>
								<span class="good-status">交易状态</span> <span class="deal-action">交易操作</span></div>

							<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$po): $mod = ($i % 2 );++$i;?><div class="single" onmouseover="this.className='singlehover'" onmouseout="this.className='single'">
									<div class="order-detail am-text-sm">
										<!--<input class="ids row-selected" type="checkbox" name="tag[]" value="<?php echo ($po["tag"]); ?>">-->
										订单号：<span class="am-link-muted num-text"><?php echo ($po["orderid"]); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;下单时间：<span class="am-link-muted num-text"><?php echo (date('Y-m-d H:i:s',$po["create_time"])); ?>&nbsp;

										<?php if(($po['status']) >= "3"): ?><b><a class="trash" href="<?php echo U('order/del?tag='.$po['tag']);?>"><img src="/Public/Home/images/iconfont-lajixiang.png" width="20" height="20"></a></b><?php endif; ?>
										</span>
									</div>
									<!-- 列表详情区域开始 -->
									<div id="table4">
										<div id="table3">
											<div id="table2">
									<div class="goodlist-wrap am-cf">
										<!-- 左边商品区域开始 -->
										<div class="three-area">
											<?php if(is_array($po['id'])): $i = 0; $__LIST__ = $po['id'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="cos">
		     <span class="am-padding-left-sm"><a href="<?php echo U('Article/detail?id='.$vo['goodid']);?>">
			     <img src="<?php echo (get_cover(get_cover_id($vo["goodid"]),'path')); ?>" width="70" height="70"/> </a>
				  <span style="width:170px;" class="am-inline-block am-text-sm"><a href="<?php echo U('Article/detail?id='.$vo['goodid']);?>"><?php echo (mb_substr(get_good_name($vo["goodid"]),0,10,'utf-8')); ?></a></span>
			     <span class="dd"><?php echo ($vo["parameters"]); ?></span>
		     </span>
													<span class="c2 am-text-warning num-text">￥<?php echo ($vo["price"]); ?></span>
													<span class="c2 num-text"><?php echo ($vo["num"]); ?></span>
		    <span class="c2 am-padding-top-sm am-padding-right-xs am-text-xs" style="white-space: nowrap">
			    <?php if($vo['status']==1||$vo['status']==2){echo "" ;}; if($vo['status']==3){echo "<a href='index.php?s=/Home/back/index/id/".$vo['id']."'>退货</a>
			    /"."<a href='index.php?s=/Home/Exchange/index/id/".$vo['id']."'>换货</a>" ;}; if($vo['status']==4){echo "<a href='index.php?s=/Home/back/detail/id/".$vo['id']."'>退货审核中</a>&nbsp;";}; if($vo['status']==5){echo "<a href='index.php?s=/Home/back/detail/id/".$vo['id']."'>同意退货</a>&nbsp;";}; if($vo['status']==6){echo "<a href='index.php?s=/Home/back/detail/id/".$vo['id']."'>退货中</a>&nbsp;";}; if($vo['status']==7){echo "<a href='index.php?s=/Home/back/detail/id/".$vo['id']."'>退货被拒绝</a>&nbsp;";}; if($vo['status']==8){echo "<a href='index.php?s=/Home/back/detail/id/".$vo['id']."'>退货完成</a>&nbsp;";}; if($vo['status']==-4){echo "<a href='index.php?s=/Home/Exchange/detail/id/".$vo['id']."'>换货审核中</a>
			    &nbsp;";}; if($vo['status']==-5){echo "<a href='index.php?s=/Home/Exchange/detail/id/".$vo['id']."'>同意换货</a>&nbsp;";}; if($vo['status']==-6){echo "<a href='index.php?s=/Home/Exchange/detail/id/".$vo['id']."'>换货中</a>&nbsp;";}; if($vo['status']==-7){echo "<a href='index.php?s=/Home/Exchange/detail/id/".$vo['id']."'>换货被拒绝</a>
			    &nbsp;";}; if($vo['status']==-8){echo "<a href='index.php?s=/Home/Exchange/detail/id/".$vo['id']."'>换货完成</a>&nbsp;";}; if($vo['iscomment']==1){echo "<br/><a href='index.php?s=/Home/comment/index/id/".$vo['id']."'>评价</a>";}; if($vo['iscomment']==2){echo "<br/><a href='index.php?s=/Home/comment/lists'>已评价</a>";}; ?></span>
												</div><?php endforeach; endif; else: echo "" ;endif; ?>
										</div>
										<!-- 左边商品结束 -->


										<div class="total-area">
											<p class="am-text-warning num-text">
											￥<?php echo ($po["total_money"]); ?></p>

											<p class="am-text-xs">(运费￥<?php echo ($po["ship_price"]); ?>)</p>
										</div>
										<div class="deal-area">
											<p class="red am-text-xs am-margin-0 ">
												<?php $status=$po['status'];if($status==13){ echo "待支付";}; if($status==1){ echo "待发货";}; if($status==2){ echo "已发货";}; if($status==3){ echo "交易成功";}; if($status==4){ echo "申请取消订单";}; if($status==5){ echo "取消被拒绝";}; if($status==6){ echo "订单已取消";}; ?>
											</p>
											<p class="am-text-xs am-margin-0 ">
												<a href="<?php echo U('order/detail?id='.$po['orderid']);?>" class="am-text-xs">订单详情</a>
											</p>
										</div>
										<div class="act-area">
											<?php $status=$po['status']; if($status==2){ echo "
												<p>
												<a class=' am-btn am-btn-warning am-btn-xs confirm' href='index.php?s=/Home/order/complete/id/".$po['orderid']."'>确认收货</a>
													<a href='index.php?s=/Home/cancel/index/id/".$po['orderid']."'>取消订单</a>
											</p>"; }; $pay=$po['ispay']; if($pay==1&&$po['status']==-1){ echo "
												<p>
													<a   href='index.php?s=/Home/cancel/index/id/".$po['orderid']."'>取消订单</a>
													<a class='am-btn am-btn-warning am-btn-xs' href='index.php?s=/Home/Pay/index/orderid/".$po['orderid']."'>前去支付</a>
												</p>"; }; if($pay==1&&$po['status']==1 ){ echo " <p>
														<a href='index.php?s=/Home/cancel/index/id/".$po['orderid']."'>取消订单</a>
													</p>"; } ; ?>

										</div>
										<!--<A href='index.php?s=/Home/cancel/index/id/".$po['orderid']."'>取消订单</a>-->
									</div>
									<!-- 列表详情区域结束 -->
								</div>
											</div>
									</div>
								</div><?php endforeach; endif; else: echo "" ;endif; ?>
						</form><?php endif; ?>

					<!-- 分页 -->
					<div class="page">
						<?php echo ($page); ?>
					</div>
					<script type="text/javascript">


						function delorder() {
							if (confirm("确认吗?")) {
								document.delform.submit();
							}

						}
						//全选的实现
						$(".check-all").click(function () {
							$(".ids").prop("checked", this.checked);
						});
						$(".ids").click(function () {
							var option = $(".ids");
							option.each(function (i) {
								if (!this.checked) {
									$(".check-all").prop("checked", false);
									return false;
								} else {
									$(".check-all").prop("checked", true);
								}
							});
						});

//						$(function(){
//							var heightLeft= $(".three-area").height();
//							var heightRight= $(".total-area").height();
//							if (heightLeft > heightRight)
//							{
//								$(".total-area").height(heightLeft);
//
//							}
//
//							else
//							{
//								$(".three-area").height(heightRight);
//							}
//						})

					</script>
					<!-- 个人中心 初始状态 end --></div>
			</div>
		</div>
	</div>

	<script type="text/javascript">_init_area();</script>
	<script type="text/javascript">
		$(".trash").click(function(){
			if(confirm("是否确认?")){
			    return true;
			}else{
				return false;
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