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
	
	<div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
	<div id="subnav" class="subnav admin-sidebar am-offcanvas">
		<div class="am-offcanvas-bar admin-offcanvas-bar">
			<ul class="am-list admin-sidebar-list">
				<?php if(is_array($nodes)): $i = 0; $__LIST__ = $nodes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
					<li>
						<?php if(!empty($sub_menu)): if(($sub_menu['allow_publish']) > "0"): ?><a class="item am-cf" href="#"><span class="<?php echo get_am_icon_by_title($sub_menu['title']);?>"></span>
									<?php echo ($sub_menu["title"]); ?></a>
								<?php else: ?>
								<?php echo ($sub_menu["title"]); endif; ?>
							<ul class="side-sub-menu am-list am-collapse admin-sidebar-sub am-in ">
								<?php if(is_array($sub_menu['_child'])): $i = 0; $__LIST__ = $sub_menu['_child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
										<?php if(($menu['allow_publish']) > "0"): ?><a class="item am-cf" href="<?php echo (U($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
											<?php else: ?>
											<a class="item am-cf" href="javascript:void(0);"><?php echo ($menu["title"]); ?></a><?php endif; ?>

										<!-- 一级子菜单 -->
										<?php if(isset($menu['_child'])): ?><ul class="subitem am-list am-collapse admin-sidebar-sub am-in">
												<?php if(is_array($menu['_child'])): foreach($menu['_child'] as $key=>$three_menu): ?><li>
														<?php if(($three_menu['allow_publish']) > "0"): ?><a class="item" href="<?php echo (U($three_menu["url"])); ?>"><?php echo ($three_menu["title"]); ?></a>
															<?php else: ?>
															<a class="item" href="javascript:void(0);"><?php echo ($three_menu["title"]); ?></a><?php endif; ?>
														<!-- 二级子菜单 -->
														<?php if(isset($three_menu['_child'])): ?><ul class="subitem side-sub-menu am-list admin-sidebar-list ">
																<?php if(is_array($three_menu['_child'])): foreach($three_menu['_child'] as $key=>$four_menu): ?><li>
																		<?php if(($four_menu['allow_publish']) > "0"): ?><a class="item" href="<?php echo U('index','cate_id='.$four_menu['id']);?>"><?php echo ($four_menu["title"]); ?></a>
																			<?php else: ?>
																			<a class="item" href="javascript:void(0);"><?php echo ($four_menu["title"]); ?></a><?php endif; ?>
																		<!-- 三级子菜单 -->
																		<?php if(isset($four_menu['_child'])): ?><ul class="subitem">
																				<?php if(is_array($four_menu['_child'])): $i = 0; $__LIST__ = $four_menu['_child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$five_menu): $mod = ($i % 2 );++$i;?><li>
																						<?php if(($five_menu['allow_publish']) > "0"): ?><a class="item" href="<?php echo U('index','cate_id='.$five_menu['id']);?>"><?php echo ($five_menu["title"]); ?></a>
																							<?php else: ?>
																							<a class="item" href="javascript:void(0);"><?php echo ($five_menu["title"]); ?></a><?php endif; ?>
																					</li><?php endforeach; endif; else: echo "" ;endif; ?>
																			</ul><?php endif; ?>
																		<!-- end 三级子菜单 -->
																	</li><?php endforeach; endif; ?>
															</ul><?php endif; ?>
														<!-- end 二级子菜单 -->
													</li><?php endforeach; endif; ?>
											</ul><?php endif; ?>
										<!-- end 一级子菜单 -->
									</li><?php endforeach; endif; else: echo "" ;endif; ?>
							</ul><?php endif; ?>
					</li>
					<!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
		</div>
	</div>
</div>
<script>
	$(function () {
		$(".side-sub-menu li").hover(function () {
			$(this).addClass("hover");
		}, function () {
			$(this).removeClass("hover");
		});
	})
</script>


	<!-- content start -->
	<div class="admin-content">

		
	<div class="am-cf am-padding ">
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">商品列表(<?php echo ($_total); ?>)</strong> /
			<small>
				<!--[-->
				<!--<?php if(!empty($rightNav)): ?>-->
				<!--<?php if(is_array($rightNav)): $i = 0; $__LIST__ = $rightNav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i;?>-->
				<!--<a href="<?php echo U('goods/index','cate_id='.$nav['id']);?>"><?php echo ($nav["title"]); ?></a>-->
				<!--<?php if(count($rightNav) > $i): ?><i class="ca"></i><?php endif; ?>-->
				<!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
				<!--<?php if(isset($article)): ?>：<a href="<?php echo U('goods/index','cate_id='.$cate_id.'&pid='.$article['id']);?>"><?php echo ($article["title"]); ?></a>-->
				<!--<?php endif; ?>-->
				<!--<?php else: ?>-->
				<!--<?php if(empty($position)): ?>全部-->
				<!--<?php else: ?>-->
				<!--<a href="<?php echo U('goods/index');?>">全部</a><?php endif; ?>-->
				<!--<?php if(is_array(C("DOCUMENT_POSITION"))): foreach(C("DOCUMENT_POSITION") as $key=>$vo): ?>-->
				<!--<?php if(($position) != $key): ?>-->
				<!--<a href="<?php echo U('goods/index',array('position'=>$key));?>"><?php echo ($vo); ?></a>-->
				<!--<?php else: ?>-->
				<!--<?php echo ($vo); ?>-->
				<!--<?php endif; ?>-->
				<!--&nbsp;-->
				<!--<?php endforeach; endif; ?>-->
				<!--<?php endif; ?>-->
				<!--]-->
				<?php if(($allow) == "0"): ?>（该分类不允许发布内容）<?php endif; ?>
				<?php if(count($model) > 1): ?>[ 模型：
					<?php if(empty($model_id)): ?><strong>全部</strong>
						<?php else: ?>
						<a href="<?php echo U('goods/index',array('pid'=>$pid,'cate_id'=>$cate_id));?>">全部</a><?php endif; ?>
					<?php if(is_array($model)): $i = 0; $__LIST__ = $model;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($model_id) != $vo): ?><a href="<?php echo U('goods/index',array('pid'=>$pid,'cate_id'=>$cate_id,'model_id'=>$vo));?>"><?php echo (get_document_model($vo,'title')); ?></a>
							<?php else: ?>
							<strong><?php echo (get_document_model($vo,'title')); ?></strong><?php endif; ?>
						&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
					]<?php endif; ?>
			</small>
		</div>
	</div>


		
	<div class="am-g am-padding am-padding-top-0">

		<!-- 按钮工具栏 -->
		<div class="am-cf">
			<div class="am-fl">
				<div class="am-inline-block">
					<?php if(($allow) > "0"): ?><button class="am-btn am-btn-default am-btn-sm document_add"
						<?php if(count($model) == 1): ?>url="<?php echo U('goods/add',array('cate_id'=>$cate_id,'pid'=>I('pid',0),'model_id'=>$model[0]));?>"<?php endif; ?>
						>新 增
						<?php if(count($model) > 1): ?><i class="btn-arrowdown"></i><?php endif; ?>
						</button>
						<?php if(count($model) > 1): ?><ul class="dropdown nav-list">
								<?php if(is_array($model)): $i = 0; $__LIST__ = $model;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
										<a href="<?php echo U('goods/add',array('cate_id'=>$cate_id,'model_id'=>$vo,'pid'=>I('pid',0)));?>"><?php echo (get_document_model($vo,'title')); ?></a>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>
							</ul><?php endif; ?>
						<?php else: ?>
						<button class="am-btn am-btn-default am-btn-sm disabled">新 增
							<?php if(count($model) > 1): ?><i class="btn-arrowdown"></i><?php endif; ?>
						</button><?php endif; ?>
				</div>
				<button class="am-btn am-btn-default am-btn-sm ajax-post" target-form="ids" url='<?php echo U("goods/setStatus",array("status"=>1));?>'>
					启 用
				</button>
				<button class="am-btn am-btn-default am-btn-sm ajax-post" target-form="ids" url='<?php echo U("goods/setStatus",array("status"=>0));?>'>
					禁 用
				</button>
				<!--<button class="am-btn am-btn-default am-btn-sm ajax-post" target-form="ids" url='<?php echo U("goods/move");?>'>移-->
					<!--动-->
				<!--</button>-->
				<!--<button class="am-btn am-btn-default am-btn-sm ajax-post" target-form="ids" url='<?php echo U("goods/copy");?>'>复-->
					<!--制-->
				<!--</button>-->
				<!--<button class="am-btn am-btn-default am-btn-sm ajax-post" target-form="ids" hide-data="true" url='<?php echo U(" Article/paste");?>'>-->
					<!--粘 贴-->
				<!--</button>-->
				<input type="hidden" class="hide-data" name="cate_id" value="<?php echo ($cate_id); ?>"/>
				<input type="hidden" class="hide-data" name="pid" value="<?php echo ($pid); ?>"/>
				<button class="am-btn am-btn-default am-btn-sm ajax-post confirm" target-form="ids" url='<?php echo U("goods/setStatus",array("status"=>-1));?>'>
					删 除
				</button>
				<!-- <button class="btn document_add" url="<?php echo U(' article
				/batchOperate',array('cate_id'=>$cate_id,'pid'=>I('pid',0)));?>">导入</button> -->

			</div>

			<!-- 高级搜索 -->
			<div class="am-search-form am-fr am-cf">
				<form action="" class="search-form am-form">
					<div class="sleft  am-input-group  am-fl">
						<input type="text" name="title" class="search-input am-padding-xs" value="<?php echo I('title');?>" placeholder="请输入标题文档">
						<a class="sch-btn" href="javascript:;" id="search" url="<?php echo U('Goods/index','pid='.I('pid',0).'&cate_id='.$cate_id,false);?>"><i class="am-icon-search"></i></a>
					</div>
				</form>
				<!--<div class="search-form am-fr am-cf">-->
				<!--<div class="sleft">-->
				<!--<div class="drop-down">-->
				<!--<span id="sch-sort-txt" class="sort-txt" data="<?php echo ($status); ?>"><?php if(get_status_title($status) == ''): ?>所有<?php else: echo get_status_title($status); endif; ?></span>-->
				<!--<i class="arrow arrow-down"></i>-->
				<!--<ul id="sub-sch-menu" class="nav-list hidden">-->
				<!--<li><a href="javascript:;" value="">所有</a></li>-->
				<!--<li><a href="javascript:;" value="1">正常</a></li>-->
				<!--<li><a href="javascript:;" value="0">禁用</a></li>-->
				<!--<li><a href="javascript:;" value="2">待审核</a></li>-->
				<!--</ul>-->
				<!--</div>-->
				<!--<input type="text" name="title" class="search-input" value="<?php echo I('title');?>" placeholder="请输入标题文档">-->
				<!--<a class="sch-btn" href="javascript:;" id="search" url="<?php echo U('goods/index','pid='.I('pid',0).'&cate_id='.$cate_id,false);?>"><i class="btn-search"></i></a>-->
				<!--</div>-->
				<!--<div class="btn-group-click adv-sch-pannel fl">-->
				<!--<button class="btn">高 级<i class="btn-arrowdown"></i></button>-->
				<!--<div class="dropdown cf">-->
				<!--<div class="row">-->
				<!--<label>更新时间：</label>-->
				<!--<input type="text" id="time-start" name="time-start" class="text input-2x" value="" placeholder="起始时间" /> - -->
				<!--<input type="text" id="time-end" name="time-end" class="text input-2x" value="" placeholder="结束时间" />-->
				<!--</div>-->
				<!--<div class="row">-->
				<!--<label>创建者：</label>-->
				<!--<input type="text" name="nickname" class="text input-2x" value="" placeholder="请输入用户名">-->
				<!--</div>-->
				<!--</div>-->
				<!--</div>-->
			</div>
		</div>

		<!-- 数据表格 -->
		<div class="data-table am-margin-top">
			<table class="am-table am-table-striped am-table-hover am-table-compact am-text-nowrap dataTable no-footer">
				<!-- 表头 -->
				<thead>
				<tr>
					<th class="row-selected row-selected">
						<input class="check-all" type="checkbox">
					</th>
					<?php if(is_array($list_grids)): $i = 0; $__LIST__ = $list_grids;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$field): $mod = ($i % 2 );++$i;?><th><?php echo ($field["title"]); ?></th><?php endforeach; endif; else: echo "" ;endif; ?>
				</tr>
				</thead>

				<!-- 列表 -->
				<tbody>
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
						<td><input class="ids" type="checkbox" value="<?php echo ($data['id']); ?>" name="ids[]"></td>
						<?php if(is_array($list_grids)): $i = 0; $__LIST__ = $list_grids;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grid): $mod = ($i % 2 );++$i;?><td><?php echo get_list_field($data,$grid);?></td><?php endforeach; endif; else: echo "" ;endif; ?>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				</tbody>
			</table>
		</div>
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


	<script src="/Public/static/thinkbox/jquery.thinkbox.js"></script>
	<script type="text/javascript" src="/Public/Admin/js/common.js"></script>
	<link href="/Public/static/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">
	<?php if(C('COLOR_STYLE')=='blue_color') echo '
		<link href="/Public/static/datetimepicker/css/datetimepicker_blue.css" rel="stylesheet" type="text/css">
		'; ?>
	<link href="/Public/static/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="/Public/static/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
	<script type="text/javascript">

		$(function () {
			$("a").each(function (i, v) {
				if ($.trim($(this).text()) == '删除') {
					$(this).click(function () {
						return confirm("确认操作?");
					})
				}
			});
			//搜索功能
			$("#search").click(function () {
				var url = $(this).attr('url');
				var query = $('.search-form').serialize();
				query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g, '');
				query = query.replace(/^&/g, '');
				if (url.indexOf('?') > 0) {
					url += '&' + query;
				} else {
					url += '?' + query;
				}
				window.location.href = url;
			});

			/* 状态搜索子菜单 */
			$(".search-form").find(".drop-down").hover(function () {
				$("#sub-sch-menu").removeClass("hidden");
			}, function () {
				$("#sub-sch-menu").addClass("hidden");
			});
			$("#sub-sch-menu li").find("a").each(function () {
				$(this).click(function () {
					var text = $(this).text();
					$("#sch-sort-txt").text(text).attr("data", $(this).attr("value"));
					$("#sub-sch-menu").addClass("hidden");
				})
			});

			//只有一个模型时，点击新增
			$('.document_add').click(function () {
				var url = $(this).attr('url');
				if (url != undefined && url != '') {
					window.location.href = url;
				}
			});

			//点击排序
			$('.list_sort').click(function () {
				var url = $(this).attr('url');
				var ids = $('.ids:checked');
				var param = '';
				if (ids.length > 0) {
					var str = new Array();
					ids.each(function () {
						str.push($(this).val());
					});
					param = str.join(',');
				}

				if (url != undefined && url != '') {
					window.location.href = url + '/ids/' + param;
				}
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