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
	
	<link rel="stylesheet" href="/Public/Home/css/index.css">

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

	<!-- 轮播图 -->
	<div class="am-slider am-slider-default" data-am-flexslider id="index-slider">
	<ul class="am-slides">
	<!-- <li><a href="./index.php?s=/home/article/lists/pid/233.html"><img src="/Public/static/amaze/i/baner888.jpg"/></a></li> -->
	<!-- <li><a href="<?php echo U('shop/index');?>"><img src="/Public/static/amaze/i/baner888.jpg"/></a></li> -->
		<li><img src="/Public/static/amaze/i/banner-01.jpg"/>
			<span><a href="<?php echo U('SelfTest/index');?>" class="btn_zj"></a></span></li>
		<li><img src="/Public/static/amaze/i/banner-02.jpg"/>
		<span><a class="am-btn am-btn-secondary am-radius" href="<?php echo U('home/article/detail/id/552');?>" target="_parent">>><b>航天无锡疗养院介绍</b></a></span>
		</li>
		<li><img src="/Public/static/amaze/i/banner-03.jpg"/></li>
	</ul>
</div>

	<!-- /轮播图 -->

	<!-- 主体 -->
	<div class="common_wrapper">

	</div>
	<!--content begin-->
	<div class="am-g am-container">
		<div class="index_tab">
			<div class="am-tabs am-padding" data-am-tabs="{noSwipe: 1}" id="doc-my-tabs">
				<ul class="am-tabs-nav am-nav am-nav-pills">
					<li class="am-active"><a href="">发现</a></li>
					<li><a href="">健康体检</a></li>
					<li><a href="">健康商城</a></li>
					<li><a href="">健康咨询</a></li>
					<li><a href="">健康讲坛</a></li>
					<li><a href="">名医堂</a></li>
				</ul>
				<div class="am-tabs-bd">
					<!--发现-->
					<div class="am-tab-panel am-active">
						<div class="am-g">
							<div class="col-sm-4 am-u-lg-4">
							<!-- 	<div class="finder_box1">
									<img src="/Public/static/amaze/i/index-01.jpg">

									<p>
										<span class="am-fl am-padding-left"><a href="<?php echo U('SelfTest/index');?>">健康互动评测</a></span>
										<span class="am-fr am-padding-right"><img src="/Public/static/amaze/i/index_arrow.png"></span>
									</p>

								</div> -->
								<div class="finder_box1">
									<a href="<?php echo U('Mall/forwardSite');?>" target="_blank"><img src="/Public/static/amaze/i/index-011.jpg"></a>

									<p>
										<span class="am-fl am-padding-left"><a href="<?php echo U('Mall/forwardSite');?>" target="_blank">关爱健康商城</a></span>
										<!-- <span class="am-fl am-padding-left"><a href="<?php echo U('Shop/index');?>">关爱健康商城</a></span> -->
										<span class="am-fr am-padding-right"><img src="/Public/static/amaze/i/index_arrow.png"></span>
									</p>
								</div>
								<div class="finder_box1 am-margin-top-sm">
									<img src="/Public/static/amaze/i/index-098.jpg">

									<p>
										<span class="am-fl am-padding-left"><a href="<?php echo U('SelfTest/index');?>">健康体检</a></span>
										<span class="am-fr am-padding-right"><img src="/Public/static/amaze/i/index_arrow.png"></span>
									</p>
								</div>


							</div>
							<div class="col-sm-4 am-u-lg-4">
								<div class="finder_box1">
									<img src="/Public/static/amaze/i/index-02.jpg">

									<p>
										<span class="am-fl am-padding-left"><a href="<?php echo U('Suite/index');?>">个人 /
											团体体检预约</a></span>
										<span class="am-fr am-padding-right"><img src="/Public/static/amaze/i/index_arrow.png"></span>
									</p>
								</div>
								<div class="finder_box1 am-margin-top-sm">
									<a href="<?php echo U('Lesson/index');?>"><img src="/Public/static/amaze/i/index-03.jpg"></a>

									<p>
										<span class="am-fl am-padding-left"><a href="<?php echo U('Lesson/index');?>">健康小知识</a></span>
										<span class="am-fr am-padding-right"><img src="/Public/static/amaze/i/index_arrow.png"></span>
									</p>
								</div>
							</div>
							<div class="col-sm-4 am-u-lg-4">
								<div class="finder_box1">
									<a href="/Home/report"><img src="/Public/static/amaze/i/index-04.jpg"></a>

									<p>
										<!-- <span class="am-fl am-padding-left"><a href="<?php echo U('SuiteOrder/finished');?>">体检报告在线查看</a></span> -->
										<span class="am-fl am-padding-left"><a href="/Home/report">体检报告在线查看</a></span>
										<span class="am-fr am-padding-right-xl"><img src="/Public/static/amaze/i/index_arrow.png"></span>
									</p>
								</div>
								<div class="finder_box1 am-margin-top-sm">
									<img src="/Public/static/amaze/i/index-05.jpg">

									<p>
										<span class="am-fl am-padding-left"><a href="<?php echo U('Question/index');?>">健康咨询——与医生直接进行交流</a></span>
										<span class="am-fr am-padding-right-xl"><img src="/Public/static/amaze/i/index_arrow.png"></span>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="am-tab-panel">
						<!--健康体检-->
						<div class="am-g am-g-collapse">
							<div class="col-sm-3 am-u-lg-3">
								<div class="health-box1">
									<p>
										<span class="btn"><a href="<?php echo U('SelfTest/index');?>">健康互动评测 >></a></span>
										<span class="tips">测评完成后智能选择套餐</span>
									</p>
								</div>
							</div>
							<div class="col-sm-3 am-u-lg-3">
								<div class="health-box2">
									<img src="/Public/static/amaze/i/health-01.jpg">
									<ul class="hl-group1">
										<?php if(is_array($suite1)): $i = 0; $__LIST__ = $suite1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): $mod = ($i % 2 );++$i;?><li>
												<dd class="am-fl"><a href="<?php echo U('Suite/detail',array('id'=>$s['id']));?>"><?php echo ($s["title"]); ?></a><span>男：￥<?php echo ($s["price_sex_1"]); ?>   女：￥<?php echo ($s["price_sex_0"]); ?></span>
												</dd>
												<dt class="am-fr"><a href="<?php echo U('Suite/detail',array('id'=>$s['id']));?>">选择</a>
												</dt>
												<div class="am-cf"></div>
											</li><?php endforeach; endif; else: echo "" ;endif; ?>
										<a href="<?php echo U('Suite/index');?>" class="select-more">选择更多</a>
									</ul>
								</div>
							</div>
							<div class="col-sm-3 am-u-lg-3">
								<div class="health-box2 ">
									<ul class="hl-group2">
										<?php if(is_array($suite2)): $i = 0; $__LIST__ = array_slice($suite2,0,3,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): $mod = ($i % 2 );++$i;?><li>
												<dd class="am-fl"><a href="<?php echo U('Suite/detail',array('id'=>$s['id']));?>"><?php echo ($s["title"]); ?></a><span>男：￥<?php echo ($s["price_sex_1"]); ?>   女：￥<?php echo ($s["price_sex_0"]); ?></span>
												</dd>
												<dt class="am-fr"><a href="<?php echo U('Suite/detail',array('id'=>$s['id']));?>">选择</a>
												</dt>
												<div class="am-cf"></div>
											</li><?php endforeach; endif; else: echo "" ;endif; ?>
										<a href="<?php echo U('Suite/index');?>" class="select-more">选择更多</a>
									</ul>
									<img src="/Public/static/amaze/i/health-02.jpg">
								</div>
							</div>
							<div class="col-sm-3 am-u-lg-3">
								<div class="health-box2 ">
									<img src="/Public/static/amaze/i/health-03.jpg">
									<ul class="hl-group3">
										<?php if(is_array($suite2)): $i = 0; $__LIST__ = array_slice($suite2,3,3,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): $mod = ($i % 2 );++$i;?><li>
												<dd class="am-fl"><a href="<?php echo U('Suite/detail',array('id'=>$s['id']));?>"><?php echo ($s["title"]); ?></a><span>男：￥<?php echo ($s["price_sex_1"]); ?>   女：￥<?php echo ($s["price_sex_0"]); ?></span>
												</dd>
												<dt class="am-fr"><a href="<?php echo U('Suite/detail',array('id'=>$s['id']));?>">选择</a>
												</dt>
												<div class="am-cf"></div>
											</li><?php endforeach; endif; else: echo "" ;endif; ?>
										<a href="<?php echo U('Suite/index');?>" class="select-more">选择更多</a>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="am-tab-panel">
						<!--健康商城-->
						<div class="am-g am-g-collapse">
							<div class="col-sm-3 am-u-lg-3">
								<div class="shop-list shop1 am-padding">
									<p class="icon-shop1"></p>

									<p class="shop-name"><a href="<?php echo U('Suite/index');?>">体检套餐</a></p>

									<p class="shop-tj">
										<?php echo ($suite1[0]['title']); ?> 男￥<?php echo ($suite1[0]['price_sex_1']); ?>
										女￥<?php echo ($suite1[0]['price_sex_0']); ?><br>
										<?php echo ($suite1[1]['title']); ?> 男￥<?php echo ($suite1[1]['price_sex_1']); ?>
										女￥<?php echo ($suite1[1]['price_sex_0']); ?>
									</p>
									<a href="<?php echo U('Suite/index');?>"><img src="/Public/static/amaze/i/arrow_health_right.png"></a>
								</div>
								<div class="shop-list shop5 am-padding">
									<p class="icon-shop5"></p>

									<p class="shop-name"><a href="<?php echo U('article/index',array('pid'=>160));?>"><?php echo ($tree[1]['title']); ?></a></p>

									<p class="shop-tj">
										<?php if(is_array($tree[1]['doc'])): $i = 0; $__LIST__ = array_slice($tree[1]['doc'],1,2,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i; echo (mb_substr($row["title"],0,5,'utf-8')); ?>￥<?php echo ($row["price"]); ?><br/><?php endforeach; endif; else: echo "" ;endif; ?>
									</p>
									<a href="<?php echo U('article/index',array('pid'=>160));?>"><img src="/Public/static/amaze/i/arrow_health_right.png"></a>
								</div>
							</div>
							<div class="col-sm-3 am-u-lg-3">
								<div class="shop-list shop2 am-padding">
									<p class="icon-shop2"></p>

									<p class="shop-name"><a href="<?php echo U('article/index',array('pid'=>164));?>"><?php echo ($tree[6]['title']); ?></a>
									</p>

									<p class="shop-tj">
										<?php if(is_array($tree[6]['doc'])): $i = 0; $__LIST__ = array_slice($tree[6]['doc'],1,2,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i; echo (mb_substr($row["title"],0,5,'utf-8')); ?>￥<?php echo ($row["price"]); ?><br/><?php endforeach; endif; else: echo "" ;endif; ?>
									</p>
									<a href="<?php echo U('article/index',array('pid'=>164));?>"><img src="/Public/static/amaze/i/arrow_health_right.png"></a>
								</div>
								<div class="shop-list shop6 am-padding">
									<p class="icon-shop6"></p>

									<p class="shop-name"><a href="<?php echo U('article/index',array('pid'=>107));?>"><?php echo ($tree[2]['title']); ?></a></p>

									<p class="shop-tj">
										<?php if(is_array($tree[2]['doc'])): $i = 0; $__LIST__ = array_slice($tree[2]['doc'],1,2,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i; echo (mb_substr($row["title"],0,5,'utf-8')); ?>￥<?php echo ($row["price"]); ?><br><?php endforeach; endif; else: echo "" ;endif; ?>
									</p>
									<a href="<?php echo U('article/index',array('pid'=>107));?>"><img src="/Public/static/amaze/i/arrow_health_right.png"></a>
								</div>
							</div>
							<div class="col-sm-3 am-u-lg-3">
								<div class="shop-list shop3 am-padding">
									<p class="icon-shop3"></p>

									<p class="shop-name"><a href="<?php echo U('article/index',array('pid'=>107));?>"><?php echo ($tree[5]['title']); ?></a>
									</p>

									<p class="shop-tj">
										<?php if(is_array($tree[5]['doc'])): $i = 0; $__LIST__ = array_slice($tree[5]['doc'],1,2,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i; echo (mb_substr($row["title"],0,5,'utf-8')); ?>￥<?php echo ($row["price"]); ?><br/><?php endforeach; endif; else: echo "" ;endif; ?>
									</p>
									<a href="<?php echo U('article/index',array('pid'=>107));?>"><img src="/Public/static/amaze/i/arrow_health_right.png"></a>
								</div>
								<div class="shop-list shop7 am-padding">
									<p class="icon-shop7"></p>

									<p class="shop-name"><a href="<?php echo U('article/index',array('pid'=>73));?>"><?php echo ($tree[3]['title']); ?></a></p>

									<p class="shop-tj">
										<?php if(is_array($tree[3]['doc'])): $i = 0; $__LIST__ = array_slice($tree[3]['doc'],1,2,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i; echo (mb_substr($row["title"],0,5,'utf-8')); ?>￥<?php echo ($row["price"]); ?><br/><?php endforeach; endif; else: echo "" ;endif; ?>
									</p>
									<a href="<?php echo U('article/index',array('pid'=>73));?>"><img src="/Public/static/amaze/i/arrow_health_right.png"></a>
								</div>
							</div>
							<div class="col-sm-3 am-u-lg-3">
								<div class="shop-list shop4 am-padding">
									<p class="icon-shop4"></p>

									<p class="shop-name"><a href="<?php echo U('article/index',array('pid'=>71));?>"><?php echo ($tree[0]['title']); ?></a>
									</p>

									<p class="shop-tj">
										<?php if(is_array($tree[0]['doc'])): $i = 0; $__LIST__ = array_slice($tree[0]['doc'],1,2,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i; echo (mb_substr($row["title"],0,5,'utf-8')); ?>￥<?php echo ($row["price"]); ?><br><?php endforeach; endif; else: echo "" ;endif; ?>
									</p>
									<a href="<?php echo U('article/index',array('pid'=>71));?>"><img src="/Public/static/amaze/i/arrow_health_right.png"></a>
								</div>
								<div class="shop-list shop8 am-padding">
									<p class="icon-shop8"></p>

									<p class="shop-name"><a href="<?php echo U('article/index',array('pid'=>159));?>"><?php echo ($tree[4]['title']); ?></a></p>

									<p class="shop-tj">
										<?php if(is_array($tree[4]['doc'])): $i = 0; $__LIST__ = array_slice($tree[4]['doc'],1,2,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i; echo (mb_substr($row["title"],0,5,'utf-8')); ?>￥<?php echo ($row["price"]); ?><br/><?php endforeach; endif; else: echo "" ;endif; ?>
									</p>
									<a href="<?php echo U('article/index',array('pid'=>159));?>"><img src="/Public/static/amaze/i/arrow_health_right.png"></a>
								</div>
							</div>
						</div>
					</div>
					<div class="am-tab-panel">
						<!--健康咨询-->
						<div class="con-box am-padding am-margin-top-xl am-margin-bottom-xl">
							<div class="con-tabs">
								<ul>
									<li class="active">全部</li>
									<?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?><li data-id="<?php echo ($c["id"]); ?>"><?php echo ($c["title"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
									<li><a href="<?php echo U('Question/lists');?>">更多&gt;&gt;</a></li>
								</ul>
							</div>
							<div class="con-tabs-content am-padding-top">
								<ul>
									<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$q): $mod = ($i % 2 );++$i;?><li class='<?php if(($mod) == "1"): ?>even<?php else: ?>odd<?php endif; ?> am-padding-xs' data-cid="<?php echo ($q["c_id"]); ?>">
											<a href="<?php echo U('Question/detail',array('id'=>$q['id']));?>">
												<dd class="am-fl am-padding-xs am-padding-left">
													<img src="/Public/static/amaze/i/icon_new_post.png">
													<?php echo ($q["title"]); ?>
													<span class="post-time am-padding-left-xl"><?php echo (time_format($q["ctime"])); ?></span>
												</dd>
												<dt class="am-fr am-padding-xs am-padding-right">
													<!--<img src="/Public/static/amaze/i/icon_user.png">-->
													<?php if(empty($q["vtime"])): ?>未
														<?php else: ?>
														已<?php endif; ?>
													回复
												</dt>
												<div class="am-cf"></div>
											</a>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>
								</ul>
							</div>
							<!--<div class="am-u-sm-12 am-u-sm-centered am-text-center">-->
							<!--<?php echo ($page); ?>-->
							<!--</div>-->
						</div>
					</div>
					<div class="am-tab-panel">
						<!--健康讲坛-->
						<div class="health-bbs ">
							<ul class="am-avg-sm-2 ">
								<?php if(is_array($lessons)): $i = 0; $__LIST__ = $lessons;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><li class="am-padding  ">
										<dd class="am-fl">
											<a href="<?php echo U('Lesson/detail',array('id'=>$row['id']));?>">
												<?php if(!empty($row["pic"])): ?><img height="160px" width="230px" src="<?php echo ($row["pic"]); ?>">
													<?php else: ?>
													<img src="/Public/static/amaze/i/img-bbs1.jpg"><?php endif; ?>
											</a>
										</dd>
										<dt class="am-fr am-padding-left-sm">
										<h4><a href="<?php echo U('Lesson/detail',array('id'=>$row['id']));?>"><?php echo ($row["title"]); ?></a>
										</h4>

										<p><?php echo (mb_substr(strip_tags($row["content"]),0,60,'utf-8')); ?>...</p>
										<a href="<?php echo U('Lesson/detail',array('id'=>$row['id']));?>">详情</a>
										</dt>
										<div class="am-cf"></div>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>

								<div class="am-cf"></div>
								<div class="am-g">
									<div class="am-u-sm-3 am-u-sm-centered am-text-center am-margin-top-sm">
										<a href="<?php echo U('Lesson/index');?>" class="viem-more">更多...</a></div>
								</div>
							</ul>
						</div>
					</div>
					<div class="am-tab-panel">
						<!--名医堂-->
						<div class="inner-tabs">
							<ul>
								<li class="active">专家风采</li>
								<!--<li><a href="<?php echo U('Env/index');?>">配套服务</a></li>-->
							</ul>
							<div class="am-cf"></div>
							<div class="inner-tabs-content">
								<div class="doctor_list am-padding am-margin">
									<div class="am-slider am-slider-default am-slider-carousel am-slider-b3 am-padding" data-am-flexslider="{itemWidth: 206, itemMargin:8,slideshow:false,controlNav: false,}">
										<ul class="am-slides">
											<?php if(is_array($doctors)): $i = 0; $__LIST__ = $doctors;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$doc): $mod = ($i % 2 );++$i;?><li>
													<a href="<?php echo U('Doctor/detail',array('id'=>$doc['id']));?>">
														<?php if(!empty($doc["pic"])): ?><img src="<?php echo ($doc["pic"]); ?>"/>
															<?php else: ?>
															<img src="/Public/static/amaze/i/doctor_lh.jpg"><?php endif; ?>
													</a>
													<br>
													<span class="am-text-center">
														<a href="<?php echo U('Doctor/detail',array('id'=>$doc['id']));?>"><?php echo ($doc["realname"]); ?>
															<?php echo ($doc["title"]); ?></a>
													</span>
												</li><?php endforeach; endif; else: echo "" ;endif; ?>
										</ul>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<!--content end-->
	<!-- /主体 -->


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

	<script>
		$(function () {
			//专家风采
			$(".inner-tabs li").first().addClass("active").siblings().removeClass("active");
			$(".inner-tabs-content .doctor-list").first().show().siblings().hide();
			$(".inner-tabs li").click(function () {
				var index = $(".inner-tabs li").index(this);
				$(this).addClass("active").siblings().removeClass("active");
				$(".inner-tabs-content .doctor_list").eq(index).fadeIn().show().siblings().hide();
			});
		});
		$(function () {
			//健康咨询
			$(".con-tabs li").first().addClass("active").siblings().removeClass("active");
			$(".con-tabs li").click(function () {
				var index = $(".con-tabs li").index(this);
				$(this).addClass("active").siblings().removeClass("active");
				var cid = $(this).data('id');
				if (!cid) {
					$(".con-tabs-content li").show();
				} else {
					$(".con-tabs-content li").hide();
					$(".con-tabs-content li[data-cid=" + cid + "]").show();
				}

			});

		});
	</script>

</body>
</html>