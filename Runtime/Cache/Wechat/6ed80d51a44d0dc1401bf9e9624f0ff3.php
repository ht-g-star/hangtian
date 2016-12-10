<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo ($meta_title); ?>|<?php echo C('WEB_SITE_TITLE');?></title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<link rel="icon" type="image/png" href="/Public/static/amaze/i/favicon.png">
	<link rel="stylesheet" href="/Public/static/amaze/css/amazeui.flat.css">
	<link rel="stylesheet" href="/Public/static/amaze/css/app.css">
	<script src="/Public/static/amaze/js/jquery.min.js"></script>
	<script src="/Public/static/amaze/js/amazeui.min.js"></script>
	
	<style>

	</style>

</head>
<body>
<!-- Header -->
<header data-am-widget="header" class="am-header am-header-default">
	<a href="javascript:history.back()" class="back"><img src="/Public/static/amaze/i/app/app-arrow-back.png"></a>

	<h1 class="am-header-title">
		<a href="#"><?php echo ($meta_title); ?></a>
	</h1>

	<?php if(!empty($right_btn)): ?><div class="am-header-right am-header-nav">
			<a href="<?php echo ($right_btn["url"]); ?>" class="">
				<i class="am-header-icon <?php echo ($right_btn["class"]); ?>" style="color: #cccccc;"></i>
			</a>
		</div><?php endif; ?>
</header>
<div class="space"></div>


	<!-- List -->
	<div data-am-widget="list_news" class="am-list-news am-list-news-default">
		<div class="am-list-news-bd order">
			<ul class="am-list">
				<!--缩略图在标题左边-->
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><div class="am-padding-xs">
						<span class="c9">订单号：</span><span class="c0"><?php echo ($row["order_no"]); ?></span>
						<span class="am-badge am-badge-primary am-fr"><?php echo (get_suite_order_status($row["status"])); ?></span>
					</div>
					<li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-left">
						<div class="am-u-sm-5 am-list-thumb">
							<a href="<?php echo U('Suite/detail',array('id'=>$row['s_id']));?>">
								<?php if(!empty($row["pic"])): ?><img src="<?php echo ($row["pic"]); ?>"/>
									<?php else: ?>
									<img src="/Public/static/amaze/i/app/app-shop-list1.jpg"/><?php endif; ?>
							</a>
						</div>
						<div class="am-u-sm-7 am-list-main">
							<h3 class="am-list-item-hd">
								<a href="<?php echo U('Suite/detail',array('id'=>$row['s_id']));?>"><?php echo ($row["title"]); ?></a>
							</h3>

							<div class="price">￥<?php echo ($row["cost"]); ?> &nbsp; x <?php echo ($row["count"]); ?></div>
							<div class="order-button am-padding-top-sm">
								<?php switch($row["status"]): case "1": ?><a href="#" class="btn">去支付</a><?php break;?>
									<?php case "20": ?><a href="#" class="btn">评价晒单</a>
										<a href="#" class="btn">再次购买</a><?php break;?>
									<?php default: endswitch;?>
								<a href="<?php echo U('SuiteOrder/detail',array('oid'=>$row['id']));?>" class="btn">查看详情</a>

							</div>
						</div>
					</li>
					<div class="space"></div><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
			<?php echo ($page); ?>
		</div>
	</div>
	<!-- Navbar -->
<div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default" id="">
	<ul class="am-navbar-nav am-cf am-avg-sm-4">
		<li>
			<a href="<?php echo U('Mall/index');?>" class='<?php if((CONTROLLER_NAME) == "Mall"): ?>current<?php endif; ?>'>
				<span class="am-icon-home"></span>
				<span class="am-navbar-label">首页</span>
			</a>
		</li>
		<li>
			<a href="<?php echo U('Suite/index');?>" class='<?php if((CONTROLLER_NAME) == "Suite"): ?>current<?php endif; ?>'>
				<span class="am-icon-clock-o"></span>
				<span class="am-navbar-label">预约</span>
			</a>
		</li>
		<li>
			<a href="<?php echo U('UserMallOrder/index');?>" class='<?php if((CONTROLLER_NAME) == "UserMallOrder"): ?>current<?php endif; ?>'>
				<span class="am-icon-file-text"></span>
				<span class="am-navbar-label">订单</span>
			</a>
		</li>
		<li>
			<a href="<?php echo U('Shopcart/index');?>" class='<?php if((CONTROLLER_NAME) == "ShoppingCar"): ?>current<?php endif; if((CONTROLLER_NAME) == "Shopcart"): ?>current<?php endif; ?>'>
				<span class="am-icon-shopping-cart"></span>
				<span class="am-navbar-label">购物车</span>
			</a>
		</li>
		<li>
			<a href="<?php echo U('User/index');?>" class='<?php if((CONTROLLER_NAME) == "User"): ?>current<?php endif; ?>'>
				<span class="am-icon-user-md"></span>
				<span class="am-navbar-label">我的</span>
			</a>
		</li>
	</ul>
</div>



<div class="am-hide">
	
</div>
<script src="/Public/Wechat/js/app.js" type="text/javascript"></script>

	<script>
	</script>

</body>
</html>