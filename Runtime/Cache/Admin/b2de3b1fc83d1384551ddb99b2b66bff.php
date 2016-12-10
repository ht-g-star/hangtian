<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo ($meta_title); ?>|<?php echo C('WEB_SITE_TITLE');?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<link rel="icon" type="image/png" href="/Public/static/amaze/i/favicon.png">
	<link rel="apple-touch-icon-precomposed" href="/Public/static/amaze/i/app-icon72x72@2x.png">
	<meta name="apple-mobile-web-app-title" content="<?php echo ($meta_title); ?>|<?php echo C('WEB_SITE_TITLE');?>"/>
	<link rel="stylesheet" href="/Public/static/amaze/css/amazeui.css"/>
	<link rel="stylesheet" href="/Public/static/amaze/css/amazeui.datatables.min.css">
	<link rel="stylesheet" href="/Public/static/amaze/css/admin.css">

	<!--[if lt IE 9]>
	<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
	<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
	<script src="/Public/static/amaze/js/amazeui.ie8polyfill.min.js"></script>
	<![endif]-->

	<!--[if (gte IE 9)|!(IE)]><!-->
	<script src="/Public/static/amaze/js/jquery.min.js"></script>
	<!--<![endif]-->

	
	<link rel="stylesheet" href="/Public/Admin/css/admin_index.css">

</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，请<a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
	以获得更好的体验！</p>
<![endif]-->

<header class="am-topbar admin-header am-topbar-inverse">
	<div class="am-topbar-brand">
		<img src="/Public/static/amaze/i/admin_logo.png"> <strong><?php echo C('WEB_SITE_TITLE');?></strong>
		<small><?php echo ($meta_title); ?></small>
	</div>

	<button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}">
		<span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

	<div class="am-collapse am-topbar-collapse" id="topbar-collapse">
		<ul class="am-nav am-margin-left-xl am-nav-pills am-topbar-nav nav_boxes">
			<?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?> nav_box">
					<a href="<?php echo (get_nav_url($menu["url"])); ?>"><span class="<?php echo get_am_icon_by_title($menu['title']);?>" title="<?php echo ($menu["id"]); ?>"></span><?php echo ($menu["title"]); ?></a>
				</li><?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
		<ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
			<li class="am-dropdown" data-am-dropdown>
				<a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
					<span class="am-icon-users"></span> <?php echo session('user_auth.username');?>
					<span class="am-icon-caret-down"></span>
				</a>
				<ul class="am-dropdown-content">
					<!--<li><a href="<?php echo U('User/updateNickname');?>"><span class="am-icon-user"></span> 修改昵称</a></li>-->
					<li><a href="<?php echo U('User/updatePassword');?>"><span class="am-icon-cog"></span> 修改密码</a></li>
					<li><a href="<?php echo U('Public/logout');?>"><span class="am-icon-power-off"></span> 退出</a></li>
				</ul>
			</li>
			<li class="am-hide-sm-only">
				<a href="javascript:;" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span>
					<span class="admin-fullText">开启全屏</span></a></li>
		</ul>
	</div>
</header>

<div class="am-cf admin-main">
	
		<!-- sidebar start -->

		<div class="admin-sidebar am-offcanvas" id="admin-offcanvas">

			<div class="am-offcanvas-bar admin-offcanvas-bar">
				<ul class="am-list admin-sidebar-list">
					<?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
						<?php if(!empty($sub_menu)): ?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>">
								<a class="am-cf" data-am-collapse="{ target: '#collapse-nav<?php echo ($i); ?>' }">
									<?php if(!empty($key)): ?><span class="<?php echo get_am_icon_by_title($key);?>"></span> <?php echo ($key); endif; ?>
									<span class="am-icon-angle-right am-fr am-margin-right"></span></a>
								<ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav<?php echo ($i); ?>">
									<?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
											<a class="am-cf" href="<?php echo (U($menu["url"])); ?>"><span class="<?php echo get_am_icon_by_title($menu['title']);?>"></span><?php echo ($menu["title"]); ?><span class="am-fr am-margin-right admin-icon-yellow"></span></a>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>

								</ul>
							</li>
							<?php if(!empty($_extra_menu)): ?>
								<?php echo extra_menu($_extra_menu,$__MENU__); endif; endif; ?>
						<!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>


				</ul>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
			</div>
		</div>
		<!-- sidebar end -->
	
	<!-- content start -->
	<div class="admin-content">

		
	<div class="am-cf am-padding ">
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">体检</strong> /
			<small><?php echo ($meta_title); ?></small>
		</div>
	</div>


		
	<div class="am-g">
		<div class="am-u-sm-12 ">
			<div class="am-btn-toolbar">
				<div class="am-u-sm-9 am-btn-group am-fl" style="margin-left:-10px;">
					<!--<button type="button" id="btn_add" class="am-btn am-btn-default am-text-primary am-btn-xs" >-->
						<!--<span class="am-icon-plus"></span> 新增-->
					<!--</button>-->
					<button type="button" id="btn_edit" class="am-btn am-btn-default am-btn-xs am-text-warning am-disabled need_select">
						<span class="am-icon-pencil-square-o"></span> 编辑
					</button>
					<button type="button" id="btn_del" class="am-btn am-btn-default  am-btn-xs am-text-danger am-disabled need_select">
						<span class="am-icon-trash-o"></span> 删除
					</button>

					<button type="button" id="allow" class="am-btn am-btn-default  am-btn-xs am-text-warning am-disabled need_select">
						<span class="am-icon-check"></span> 通过
					</button>

				</div>
			</div>
			<br>
		</div>
	</div>

	<div class="am-g am-datatable-hd">
		<div class="am-u-sm-12 ">
			<form class="am-form">
				<div id="scrollable-table"> <!--class="am-scrollable-horizontal"-->
					<table class="am-table am-table-striped am-table-hover am-table-compact am-text-nowrap" id="data-list"  >
						<thead>
						<tr>
							<th>ID</th>
							<th>套餐标题</th>
							<th>评价时间</th>
							<th>评价内容</th>
							<th>评价等级</th>
							<th>状态</th>
						</tr>
						</thead>
					</table>

				</div>
			</form>
		</div>
	</div>



	</div>
	<!-- content end -->
</div>
<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>

<footer>
</footer>

<script>
	(function () {
		var ThinkPHP = window.Think = {
			"ROOT": "", //当前网站地址
			"APP": "/admin.php?s=", //当前项目地址
			"PUBLIC": "/Public", //项目公共目录地址
			"DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
			"MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
			"VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
		}
	})();
</script>
<script src="/Public/static/amaze/js/amazeui.min.js"></script>
<script src="/Public/static/amaze/js/amazeui.datatables.js"></script>
<script src="/Public/static/amaze/js/app.js"></script>
<script src="/Public/Admin/js/common.js"></script>

<script type="text/javascript" src="/Public/static/think.js"></script>


	<script src="/Public/static/thinkbox/jquery.thinkbox.js"></script>
	<script type="text/javascript" src="/Public/Admin/js/common.js"></script>
	<script type="text/javascript" src="/Public/Admin/js/suite_comment.js"></script>

<script>
	$(function() {
//		var hDIv = $(".admin-sidebar ");
//		if (hDIv.length > 0) {
////			hDIv.css("overflow", "auto");
//			$(window).resize(function() {
//				var _addHeight = $(window).height() - $("body").outerHeight(true);
//				var _height = hDIv.height();
//				hDIv.height(_height + _addHeight - (hDIv.outerHeight(true) - _height) / 2);
//			}).resize();
//		}
	});
</script>
</body>
</html>