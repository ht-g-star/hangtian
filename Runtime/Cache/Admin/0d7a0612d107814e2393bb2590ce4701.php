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
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">订单管理</strong> /
			<small>订单列表</small>
		</div>
	</div>


		
	<div class="am-g am-padding am-padding-top-0">
		<!-- 按钮工具栏 -->
		<div class="cf">
			<div class="fl">
				<!--<a class="btn am-btn am-btn-default am-text-primary am-btn-xs" href="<?php echo U('add?status='.$status);?>"> <span class="am-icon-plus"></span> 新 增</a>-->
				<a class="btn am-btn am-btn-default am-btn-xs  " id="out" url="<?php echo U('out');?>">导出</a>
				<button class="btn am-btn am-btn-default am-btn-xs ajax-post confirm" url="<?php echo U('del');?>" target-form="ids">
					删 除
				</button>
			</div>

			<!-- 高级搜索 -->
			<div class="am-search-form    am-margin-bottom">
				<form action="" class="search-form am-form am-text-sm ">
					<div class="am-g">
						<div class="am-u-sm-12">
							<ul class="am-nav am-nav-tabs am-nav-justify">
								<li class='<?php if(($status) == "0"): ?>am-active<?php endif; ?>'>
									<a href="<?php echo U('index',array('status'=>0));?>">所有订单</a></li>
								<li class='<?php if(($status) == "1"): ?>am-active<?php endif; ?>'>
									<a href="<?php echo U('index',array('status'=>1));?>">已提交订单</a></li>
								<li class='<?php if(($status) == "2"): ?>am-active<?php endif; ?>'>
									<a href="<?php echo U('index',array('status'=>2));?>">已发货订单</a></li>
								<li class='<?php if(($status) == "3"): ?>am-active<?php endif; ?>'>
									<a href="<?php echo U('index',array('status'=>3));?>">已签收订单</a></li>
								<li class='<?php if(($status) == "6"): ?>am-active<?php endif; ?>'>
									<a href="<?php echo U('index',array('status'=>6));?>">已关闭订单</a></li>
							</ul>
						</div>
					</div>
					<br>

					<div class="sleft  am-input-group  am-fl">
						<label for="orderid">订单号:</label><input type="text" name="orderid" id="orderid" class="search-input am-padding-xs " value="<?php echo I('orderid');?>" placeholder="订单号">
						| <label>下单时间:</label>
						<input type="date" name="begin_time" class="search-input am-padding-xs" id="time-start" value="<?php echo I('begin_time');?>" placeholder="开始">~
						<input type="date" name="end_time" class="search-input am-padding-xs" id="time-end" value="<?php echo I('end_time');?>" placeholder="截至">
						|
						<label>买家:</label><input type="text" name="customer_name" id="customer_name" class="search-input am-padding-xs " value="<?php echo I('customer_name');?>" placeholder="买家姓名">
						|<label>收货人:</label><input type="text" name="receiver_name" id="receiver_name" class="search-input am-padding-xs " value="<?php echo I('receiver_name');?>" placeholder="收货人">

						<button type="button" id="search" class="am-btn am-btn-primary am-icon-search" url="<?php echo U('Admin/Order/index');?>">
							搜索
						</button>

					</div>
				</form>
				<br>
			</div>

			<div class="data-table table-striped am-scrollable-horizontal am-margin-top">
				<table class="am-table am-table-striped   am-table-hover am-table-compact am-text-nowrap dataTable no-footer">
					<thead>
					<tr>
						<th class="row-selected am-padding-right-0">
							<input class="checkbox check-all" type="checkbox">
						</th>
						<th class="am-padding-right-0">ID</th>
						<th class="am-padding-right-0">订单号</th>
						<th class="am-padding-right-0">会员号</th>
						<th class="am-padding-right-0">会员姓名</th>
						<th class="am-padding-right-0">身份证号</th>
						<th class="am-padding-right-0">收货人</th>
						<th class="am-padding-right-0">收货电话</th>
						<th class="am-padding-right-0">金额</th>
						<th class="am-padding-right-0">状态</th>
						<th class="am-padding-right-0">下单时间</th>
						<th class="am-padding-right-0">更新时间</th>
						<th class="am-padding-right-0">支付方式</th>
						<th class="am-padding-right-0">用积分</th>
						<th class="am-padding-right-0">优惠券ID</th>
						<th class="am-padding-right-0">备注</th>
						<th class="am-padding-right-0">操作</th>
					</tr>
					</thead>
					<tbody>
					<?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="row">
								<td><input class="ids row-selected" type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>"></td>
								<td><?php echo ($vo["id"]); ?></td>
								<td><a title="编辑" href="<?php echo U('edit?id='.$vo['id'].'&pid='.$pid);?>"><?php echo ($vo["orderid"]); ?></a></td>
								<td><?php echo ($vo["card_num"]); ?></td>
								<td><?php echo ($vo["realname"]); ?></td>
								<td><?php echo ($vo["id_num"]); ?></td>
								<td><?php echo ($vo["receiver_name"]); ?></td>
								<td><?php echo ($vo["receiver_tel"]); ?></td>

								<td><?php echo ($vo["total_money"]); ?></td>
								<td>
									<?php if(($vo["status"]) == "-1"): ?>未付款<?php endif; ?>
									<?php if(($vo["status"]) == "1"): ?>已提交<?php endif; ?>
									<?php if(($vo["status"]) == "2"): ?>已发货<?php endif; ?>
									<?php if(($vo["status"]) == "3"): ?>已签收<?php endif; ?>
									<?php if(($vo["status"]) == "4"): ?>取消订单<?php endif; ?>
									<?php if(($vo["status"]) == "5"): ?>取消订单拒绝<?php endif; ?>
									<?php if(($vo["status"]) == "6"): ?>已关闭<?php endif; ?>
								</td>
								<td><?php echo (date('Y-m-d H:i:s',$vo["create_time"])); ?></td>
								<td><?php echo (date('Y-m-d H:i:s',(isset($vo["pay_time"]) && ($vo["pay_time"] !== ""))?($vo["pay_time"]):'')); ?></td>
								<td><?php echo ($vo["pay_type"]); ?></td>
								<td><?php echo ($vo["score"]); ?></td>
								<td><?php echo ($vo["coupon"]); ?></td>
								<td><?php echo ($vo["message"]); if(($vo["is_auto_finish"]) == "1"): ?>-自动收货<?php endif; ?></td>
								<td>
									<a title="编辑" href="<?php echo U('edit?id='.$vo['id']);?>">编辑</a>
									<?php if(($vo["status"]) == "1"): ?><a title="发货" href="<?php echo U('send?id='.$vo['id']);?>">发货</a>
										<!--<a class="confirm ajax-get" title="删除" href="<?php echo U('del?id='.$vo['id']);?>">删除</a>--><?php endif; ?>
									<?php if(($vo["status"]) == "2"): ?><a title="发货" href="<?php echo U('complete?id='.$vo['id']);?>">完成</a>
										<!--<a class="confirm ajax-get" title="删除" href="<?php echo U('del?id='.$vo['id']);?>">删除</a>--><?php endif; ?>
									<?php if(($vo["status"]) == "3"): ?><!--<a class="confirm ajax-get" title="删除" href="<?php echo U('del?id='.$vo['id']);?>">删除</a><em></em>--><?php endif; ?>
								</td>
							</tr>
							<tr class="goods_info am-hide">
								<td colspan="12">
									<table class="am-table am-table-compact ">
										<?php if(is_array($vo["goods"])): $i = 0; $__LIST__ = $vo["goods"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
												<td>名称:
													<a href="/index.php?s=/Home/Article/detail/id/<?php echo ($vo['goodid']); ?>" target="_blank">
														<img src="<?php echo (get_cover(get_cover_id($vo["goodid"]),'path')); ?>" width="40" height="40"/><?php echo (get_good_name($vo["goodid"])); ?>
													</a>
												</td>
												<td align="center">规格:
													<span class="weight"><?php echo ((isset($vo["parameters"]) && ($vo["parameters"] !== ""))?($vo["parameters"]):"无"); ?></span></td>
												<td align="center">价格:<?php echo (get_good_price($vo["goodid"])); ?></td>
												<td align="center">数量:<?php echo ($vo["num"]); ?></td>

											</tr><?php endforeach; endif; else: echo "" ;endif; ?>
									</table>
								</td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>
						<?php else: ?>
						<td colspan="6" class="text-center"> aOh! 暂时还没有内容!</td><?php endif; ?>
					</tbody>
				</table>

				<!-- 分页 -->
				<div class="page">
					<?php echo ($_page); ?>
				</div>
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


	<link href="/Public/static/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">
	<?php if(C('COLOR_STYLE')=='blue_color') echo '
		<link href="/Public/static/datetimepicker/css/datetimepicker_blue.css" rel="stylesheet" type="text/css">
		'; ?>
	<link href="/Public/static/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="/Public/static/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
	<script type="text/javascript" src="/Public/Admin/js/common.js"></script>

	<script type="text/javascript">
		$(function () {
			//搜索功能
			var status = '<?php echo ($status); ?>';
			$("#search").click(function () {
				var url = $(this).attr('url');
				var query = $('.search-form').find('input').serialize();
				query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g, '');
				query = query.replace(/^&/g, '');
				if (status != '') {
					query = 'status=' + status + "&" + query;
				}
				if (url.indexOf('?') > 0) {
					url += '&' + query;
				} else {
					url += '?' + query;
				}
				window.location.href = url;
			});

			$("#out").click(function () {
				var url = $(this).attr('url');
				var query = $('.search-form').find('input').serialize();
				query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g, '');
				query = query.replace(/^&/g, '');
				if (status != '') {
					query = 'status=' + status + "&" + query;
				}

				var ids = $("input.ids:checked").serialize();
				if (ids) {
					query += ids;
				}
				if (url.indexOf('?') > 0) {
					url += '&' + query;
				} else {
					url += '?' + query;
				}
				window.location.href = url;
			});


			//回车自动提交
			$('.search-form').find('input').keyup(function (event) {
				if (event.keyCode === 13) {
					$("#search").click();
				}
			});

			$('#time-start').datetimepicker({
				format: 'yyyy-mm-dd',
				language: "zh-CN",
				minView: 2,
				autoclose: true
			});

			$('#time-end').datetimepicker({
				format: 'yyyy-mm-dd',
				language: "zh-CN",
				minView: 2,
				autoclose: true
			});
		})
		//导航高亮

		$("tr.row").click(function () {
			$(this).next("tr.goods_info").toggleClass("am-hide");
		});
	</script>

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