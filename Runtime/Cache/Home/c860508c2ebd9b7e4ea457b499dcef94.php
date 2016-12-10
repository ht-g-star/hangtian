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
	
	<style>
		.selecter a {
			border: 1px solid #ccc;
			color: #111;
			font-size: .8em;
		}

		.selecter a.cur {
			border: 1px solid red;
			color: red;
		}

		.am-topbar {
			position: relative;
			margin-bottom: 0;
		}

		.am-with-topbar-fixed-top {
			padding-top: 0;
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

	<meta name="description" content="<?php echo C('WEB_SITE_DESCRIPTION');?>">
	<meta name="keywords" content="<?php echo C('WEB_SITE_KEYWORD');?>"/>
	<div class="am-g am-container">
		<div class="breadcrumb-box">
			<ol class="am-breadcrumb am-breadcrumb-slash">
				<li><a href="<?php echo U('Home/Index/index');?>">首页</a></li>
				<li><a href="<?php echo U('Home/Shop/index');?>">健康商城</a></li>
				<li class="am-active"><?php echo ($meta_title); ?></li>
			</ol>
		</div>

		<div class="details am-g am-container">
			<div class="details_left am-u-sm-6">
				<!-- 商品描述-->
				<div class="details_left_top">
					<!-- jqzoom-->
					<div id="zoom">
						<div class="jqzoom img" cid="spec-n1">
							<img height="350" src="<?php echo (get_cover($info["cover_id"],'path')); ?>" jqimg="<?php echo (get_cover($info["cover_id"],'path')); ?>" width="350">
						</div>
					</div>
					<!-- wwww over-->
				</div>
			</div>
			<div class="details_right am-u-sm-6">
				<!-- 商品参数-->
				<div class="dl_goods_info">
					<h1 class="dginfo_h2" id="tit"><?php echo ($info["title"]); ?></h1>
					<hr>
					<p class="am-cf">
						价格：<span class="am-text-warning am-text-xl num-text">

							￥<em class="price" id="resetprice">
						<?php if(!empty($info['groupprice'])){ $price= explode('、',$info['groupprice']);}else{ $price="";} ?>
						<?php if(!empty($price)): if(is_array($price)): $k = 0; $__LIST__ = array_slice($price,0,1,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k; echo ($v); endforeach; endif; else: echo "" ;endif; endif; ?>
						<?php if(empty($price)): echo ($info['price']); endif; ?>
					</em>
						</span>
						<span class="am-text-default am-link-muted am-padding-xs am-fr">销量：<?php echo ($info["sale"]); ?></span>

					</p>
					<hr>
					<ul class="dginfo_info">
						<li class="dginfo_info_last am-g am-padding-top am-padding-bottom-xs">
							<div class="am-u-sm-3">品牌：</div>
							<?php echo ($info["brand"]); ?><span></span>
						</li>
						<li class="dginfo_info_last am-g am-padding-top am-padding-bottom-xs">
							<div class="am-u-sm-3">库存：</div>
							<span id="stock"><?php echo ($info["stock"]); ?></span>
						</li>
						<li>
							<form action="<?php echo U('order/add');?>" name="orderform" id="orform" method="post" onsubmit="return trySubmit()">
								<input type="hidden" name="id[]" value="<?php echo ($info["id"]); ?>"/>
								<input type="hidden" name="price[]" id="inputprice" value="<?php echo ($info["price"]); ?>"/>
								<input type="hidden" name="sort[]" id="inputsort" value="<?php echo ($info["id"]); ?>"/>
								<input type="hidden" name="parameters[]" id="inputparameters"/>

								<div class="am-g am-padding-top am-padding-bottom-xs">
									<div class="am-u-sm-3 quantity">数量：</div>
									<div class="am-u-sm-4 am-u-end am-padding-left-0">
										<div class="am-input-group">
		                                    <span class="am-input-group-btn">
		                                        <button class="am-btn am-btn-default" id="minus_btn" onclick="reduce()" type="button">
			                                        -
		                                        </button>
		                                    </span>
											<input type="number" id="num" name="num[]" class="am-form-field am-text-center goodnum" value="1">
		                                    <span class="am-input-group-btn">
		                                        <button class="am-btn am-btn-default" id="plus_btn" type="button" onclick="add()">
			                                        +
		                                        </button>
		                                    </span>
										</div>
									</div>
								</div>
								<hr>
							</form>
						</li>
						<?php if(!empty($info['parameters'])): ?><li class="weight pp_prop_wrap am-g  am-padding-bottom">
								<div class="am-u-sm-3"><?php echo ($info["parameters"]); ?>：</div>
								<div class="am-u-sm-9 am-u-end am-padding-left-0 selecter">
									<?php if(!empty($info['parameters_value'])){ $datalist= explode('、',$info['parameters_value']);}else{ $datalist="";} ?>
									<?php if(!empty($datalist)): if(is_array($datalist)): $k = 0; $__LIST__ = $datalist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><a href="javascript:void(0)" class="am-margin-right-xs am-padding-xs <?php if($k == 1): ?>cur<?php else: endif; ?> " title="<?php echo ($v); ?>" onclick='setprice(<?php echo ($k); ?>);return false;'><?php echo ($v); ?></a><?php endforeach; endif; else: echo "" ;endif; endif; ?>
								</div>
							</li><?php endif; ?>

						<script>

							function setprice(num) {
								price = null;
								var str = '<?php echo ($info["groupprice"]); ?>';
								var value = str.split("、");
								var i = num - 1;
								$("#resetprice").text(value[i]);
								$("#inputprice").val(value[i])
								$(".weight a").eq(i).addClass("cur").siblings().removeClass("cur");
								;
								var para = $(".weight a").eq(i).text();
								$("#inputparameters").val(para);
							}
						</script>

					</ul>
					<!-- buy-->
					<div class="addcart_box">
						<a rel="nofollow" href="javascript:void(0)" onclick='order(<?php echo ($info["id"]); ?>);return false;' class="am-btn am-btn-secondary am-padding-sm am-radius am-padding-left-xl am-padding-right-xl">立刻购买</a>
						<a rel="nofollow" href="javascript:void(0)" onclick='buy(<?php echo ($info["id"]); ?>);return false;' class="am-btn am-btn-warning am-padding-sm am-radius am-padding-left-lg am-padding-right-lg am-margin-left-sm"><span class="am-icon-shopping-cart"></span>
							加入购物车</a>

						<!-- 购物车计算浮动层 begin -->
						<div id="showIncludeCart" class="am-modal am-modal-no-btn" tabindex="-1">
							<div class="am-modal-dialog">
								<div class="am-modal-hd">
									<h4 class="am-popup-title">购物车</h4>
									<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
								</div>
								<div class="am-modal-bd">
									<div class="buy_icon"></div>
									<div class="buy_pop_top">
										<div class="title">此商品已成功放入购物车</div>
										<div class="font">购物车共 <font id="totalnum" class="red"></font> 件宝贝<span>合计 <font
												class="red" id="fee"></font></span></div>
										<div class="btn_continue">
											<div class="pop_btn_r"><a
													onclick="$('#showIncludeCart').modal('close');return false;"
													href="javascript:void(0);">继续购物</a></div>
											<div class="btn_cart"><a href="<?php echo U('shopcart/index');?>">去购物车结算</a></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- 购物车计算浮动层 over -->
					</div>
					<!-- buy-->
					<p>

					<span>收藏产品：<a href="javascript:vod(0);" onclick="favor();return false;">
						<img title="收藏按钮" src="/Public/Home/images/icon_favorite_pro.jpg"></a>
					</span>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<span>&nbsp;&nbsp;&nbsp;
							分享到：<a title="分享到新浪微博" target="_blank"
							       href="http://service.t.sina.com.cn/share/share.php?title=<?php echo ($info["title"]); ?>。（来自<?php echo C('SITENAME');?>）&amp;url=<?php echo C('DOMAIN'); echo U('Article/detail?id='.$info['id']);?>&amp;pic=<?php echo C('DOMAIN'); echo (get_cover($info["cover_id"],'path')); ?>"
							       data-item="sina"
							       class="J_vivo_share">
								<img alt="新浪微博" src="/Public/Home/images/icon_sina_weibo.jpg" app="b2c"> </a>
					        <a title="分享到腾讯微博" target="_blank"
					           href="http://v.t.qq.com/share/share.php?title=<?php echo ($info["title"]); ?>（来自<?php echo C('SITENAME');?>）。&amp;url=<?php echo C('DOMAIN'); echo U('Article/detail?id='.$info['id']);?>&amp;pic=<?php echo C('DOMAIN'); echo (get_cover($info["cover_id"],'path')); ?>"
					           data-item="tencent" class="J_vivo_share">
						        <img alt="腾讯微博" src="/Public/Home/images/icon_tencent_weibo.jpg" app="b2c"> </a>
					        <a target="_blank" title="分享到人人网"
					           href="http://widget.renren.com/dialog/share?resourceUrl=<?php echo C('DOMAIN'); echo U('Article/detail?id='.$info['id']);?>;srcUrl=<?php echo C('DOMAIN');?>&amp;title=<?php echo ($info["title"]); ?>。（来自<?php echo C('SITENAME');?>）&amp;pic=<?php echo C('DOMAIN'); echo (get_cover($info["cover_id"],'path')); ?>"
					           data-item="renren" class="J_vivo_share">
						        <img alt="人人网" src="/Public/Home/images/icon_renrenwang.jpg" app="b2c"> </a>
						</span></p>

				</div>
			</div>
			<!-- 商品描述结束details_left_top-->

			<!-- 商品参数-->
			<div class="am-g dl_goods_detail am-container">
				<!-- 商品描述-->
				<div class="am-u-sm-12 ">
					<div data-am-widget="tabs" class="am-tabs am-tabs-default shop-content">
						<ul class="am-tabs-nav am-cf" data-am-scrollspy-nav="{offsetTop: 100}" data-am-sticky>
							<li class=""><a href="#header1">商品详情</a></li>
							<!-- <li><a href="#record">成交记录</a></li> -->
							<li><a href="#eva">评价(<?php echo (get_comment_count($info["id"])); ?>条)</a></li>
							<li><a href="#comments">咨询</a></li>
						</ul>

						<div id="goods_content" class="am-tabs-bd">
							<div class="am-text-lg am-padding">
								<font style="size:20px;color:red;"><strong>关于发货:</strong></font>&nbsp;您的订单完成支付后，我们会在2个工作日内安排发货。无需派送的商品除外。</h3>
							</div>
							<div id="header1" data-tab-panel-0 class="choose"><?php echo ($info["content"]); ?></div>
							<!--<div id="header2" class="choose" style="display:none">-->
							<!--<p><span>品牌：<?php echo ((isset($info["brand"]) && ($info["brand"] !== ""))?($info["brand"]):'无'); ?></span>-->
							<!--<span>名称：<?php echo ($info["title"]); ?></span></p>-->

							<!--<p><span>重量：<?php echo ($info["weight"]); ?>g</span><span>单价：<?php echo ($info["price"]); ?></span></p>-->

							<!--<p><span>产地：<?php echo ($info["area"]); ?></span><span>包装：<?php echo ((isset($info["package"]) && ($info["package"] !== ""))?($info["package"]):'无'); ?></span></p>-->

							<!--<p><span>配件：<?php echo ((isset($info["brand"]) && ($info["brand"] !== ""))?($info["brand"]):'无'); ?></span><span>属性：<?php echo ((isset($info["brand"]) && ($info["brand"] !== ""))?($info["brand"]):'无'); ?><span></p>-->
							<!--</div>-->

						<!-- 	<div id="record" data-tab-panel-1 class="am-padding-top-lg am-margin-bottom-0 am-padding-bottom-0">
								<div class="am-g am-padding">
									<div class="am-titlebar am-titlebar-multi am-margin-0 am-padding-0">
										<h2 class="am-titlebar-title">成交记录
											<span class="am-text-sm am-link-muted am-padding-left">共<span class="am-text-warning num-text"><?php echo ($records_count); ?></span>条</span>
										</h2>
									</div>
									<table class="am-table">
										<thead>
										<tr>
											<th>姓名</th>
											<th>创建时间</th>
											<th>规格</th>
											<th>数量</th>
										</tr>
										</thead>
										<tbody>
										<?php if(is_array($records)): $i = 0; $__LIST__ = $records;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$r): $mod = ($i % 2 );++$i;?><tr>
												<td><?php echo (cover_name($r["realname"])); ?></td>
												<td class="num-text"><?php echo (time_format($r["create_time"])); ?></td>
												<td class="num-text am-text-warning"><?php echo ($r["parameters"]); ?></td>
												<td class="num-text am-text-warning"><?php echo ($r["num"]); ?></td>
											</tr><?php endforeach; endif; else: echo "" ;endif; ?>
										</tbody>
									</table>
								</div>
							</div> -->
							<div id="eva" data-tab-panel-2 class="choose am-padding-top">
								<div class="am-g am-padding">
									<div class="am-titlebar am-titlebar-multi am-margin-0 am-padding-0">
										<h2 class="am-titlebar-title">评价
											<span class="am-text-sm am-link-muted am-padding-left">共<span class="num-text am-text-warning"><?php echo ($count); ?></span>条评论,<span class="num-text am-text-warning"><?php echo ((isset($rate) && ($rate !== ""))?($rate):'98.2'); ?>%</span>好评率</span>
										</h2>
									</div>
									<br>
									<table class="am-table">
										<thead>
										<tr>
											<th width="10%">姓名</th>
											<th width="60%">内容</th>
											<th width="10%">评分</th>
											<th width="20%">评价时间</th>
										</tr>
										</thead>
										<tbody>
										<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$r): $mod = ($i % 2 );++$i;?><tr>
												<td><?php echo (cover_name(get_regname($r["uid"]))); ?></td>
												<td class="num-text"><?php echo ($r["content"]); ?>
												</td>
												<td class="num-text am-text-danger"><?php echo ($r["goodscore"]); ?></td>
												<td class="num-text am-text-warning"><?php echo (time_format($r["create_time"])); ?></td>
											</tr>
											<!--<tr>-->
											<!--<td colspan="4">-->
											<!--<div class="replay-box am-text-sm am-padding-xs">-->
											<!--<span class="am-text-primary">航疗：</span>亲，我们会用最好的服务提供给您！-->
											<!--</div>-->
											<!--</td>-->
											<!--</tr>--><?php endforeach; endif; else: echo "" ;endif; ?>
										</tbody>
									</table>
								</div>
							</div>

							<div id="comments" data-tab-panel-3 class="choose am-padding-top">
								<div class="am-g am-padding">
									<div class="am-titlebar am-titlebar-multi am-margin-0 am-padding-0">
										<h2 class="am-titlebar-title">商品咨询 </h2>
									</div>
									<br>
									<table class="am-table">
										<thead>
										<tr>
											<th width="10">姓名</th>
											<th width="50">内容</th>
											<th width="10">咨询时间</th>
										</tr>
										</thead>
										<tbody>
										<?php if(is_array($comments)): $i = 0; $__LIST__ = $comments;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$r): $mod = ($i % 2 );++$i;?><tr>
												<td><?php echo (cover_name(get_regname($r["c_id"]))); ?></td>
												<td class="num-text"><?php echo ($r["content"]); ?>
												</td>
												<td class="num-text am-text-warning"><?php echo (time_format($r["ctime"])); ?></td>
											</tr>
											<tr>
												<td colspan="4">
													<div class="replay-box am-text-sm am-padding-xs">
														<span class="am-text-primary"><?php echo C('WEB_SITE_TITLE');?>：</span><?php echo ((isset($r["reply"]) && ($r["reply"] !== ""))?($r["reply"]):'亲，我们会用最好的服务提供给您！'); ?>
													</div>
												</td>
											</tr><?php endforeach; endif; else: echo "" ;endif; ?>
										</tbody>
									</table>
								</div>
								<form action="#" id="comment_form" method="post" class="am-form am-form-horizontal">
									<div class="am-g">
										<div class="am-u-lg-10 am-u-sm-12">
											<textarea name="comments" placeholder="可以再次写下咨询内容"></textarea>
										</div>
										<div class="am-u-lg-2 am-u-sm-12">
											<button class="am-btn am-btn-primary am-btn-block">提交</button>
										</div>
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>
				<!-- 商品描述结束-->

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
	<script type="text/javascript">//var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1257408010'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/z_stat.php%3Fid%3D1257408010' type='text/javascript'%3E%3C/script%3E"));</script>
	

	
</div>

	<SCRIPT src="/Public/static/js/base.js" type=text/javascript></SCRIPT>
	<SCRIPT src="/Public/static/js/jqueryzoom-jcarousel.js" type="text/javascript"></SCRIPT>

	<script type="text/javascript">
		var gooid = "<?php echo ($info['id']); ?>";
		function comment(id) {    //user函数名 一定要和action中的第三个参数一致上面有
			var id = id;
			$.get('<?php echo U("article/comment");?>', {p: id, goodid: gooid}, function (data) {  //用get方法发送信息到UserAction中的user方法
				$("ul#commentbox").replaceWith("<ul class='item_wrap' id='commentbox'>" + data + "</ul>"); //user一定要和tpl中的一致
			});
		}
	</script>


	<!--[if lte IE 6]>
	<script type="text/javascript">
		// 浮动对话框
		$(document).ready(function () {
			var domThis = $('#dialog')[0];
			var wh = $(window).height() / 2;
			$("body").css({
				"background-image": "url(about:blank)",
				"background-attachment": "fixed"
			});
			domThis.style.setExpression('top', 'eval((document.documentElement).scrollTop + ' + wh + ') + "px"');
		});
	</script>
	<![endif]-->


	<script type="text/javascript">
		function trySubmit() {
			if (this.submitFlag == 1) {
				alert('数据已经提交，请勿再次提交。');
				return false;
			}
			else {
				this.submitFlag = 1;
				return true;
			}
		}
	</script>
	<script type="text/javascript">
		//立即购买
		function order(gid) {
			var stock = parseInt($("#stock").text());
			if (stock <= 0) {
				topAlert("库存不足,无法购买!");
				return;
			}
			var uexist = "<?php echo is_login();?>";
			if (uexist > 0) {
				var gprice = $("em.price").eq(0).text();//价格
				var parameters = $("a.cur").text();//参数
				var _string;
				if ($(".weight").length > 0) {
					var s = $(".weight .cur").index() + 1;
					_string = (gid) + (s) + "";
				} else {
					_string = (gid) + "";
				}
				$("#inputprice").val(gprice);
				$("#inputsort").val(_string);
				$("#inputparameters").val(parameters);
				document.orderform.submit();
			} else {
				topAlert("请先登录!");
			}
		}
		//收藏
		function favor() {
			var uexist = "<?php echo get_username();?>";
			if (uexist) {
				var favorid = '<?php echo ($info["id"]); ?>';
				$.ajax({
					type: 'post', //传送的方式,get/post
					url: '<?php echo U("Collect/add");?>', //发送数据的地址
					data: {id: favorid},
					dataType: "json",
					success: function (data) {
						alert(data.msg)
					}
					,
					error: function (event, XMLHttpRequest, ajaxOptions, thrownError) {
						alert(XMLHttpRequest + thrownError);
					}
				})
			}

			else {
				showBg();
			}

		}

		//加入购物车
		function buy(i) {
			var stock = parseInt($("#stock").text());
			if (stock <= 0) {
				topAlert("库存不足,无法购买!");
				return;
			}
			var gid = i;
			var url = '<?php echo U("Shopcart/addItem");?>';//地址
			var gnum = $(".goodnum").val();//数量
			var gprice = $(".price").eq(0).text();//价格
			var src = $(".img img").attr("src");//图片
			var image = '<img src="' + src + '"width="40" height="40">';
			var href = "<a  href='<?php echo U('Article/detail ? id = '.$info['id']);?>'>";
			var title = $("#tit").text();//名称
			var parameters = $(".weight .cur").text();//参数
			if ($(".weight").length > 0) {
				var s = $(".weight .cur").index() + 1;
				var string = String(gid) + String(s);
			}
			else {
				var string = String(gid);
			}

			$.ajax({
				type: 'post', //传送的方式,get/post
				url: '<?php echo U("Shopcart/addItem");?>', //发送数据的地址
				data: {
					id: gid, num: gnum, price: gprice, i: parameters, sort: string, 'stock': stock
				},
				dataType: "json",
				success: function (data) {
					var html = '<li><dl><dt class="mini-img">' + href + image + '</a><dd><span class="mini_title">' + href + title + '</a></span><span class="mini-cart-count red"> ￥'
							+ gprice + '<em class="' + string + '">*' + gnum + '</em></span>' + '</dd><dd><span class="mini-cart-info">' + parameters + '</span><span class="mini-cart-del"><a name="' + string + '" rel="del"  onclick="delcommon(event);return false;">删除</a></span></dd>' + '</dl></li>';//创建节点〉
					if (data.exsit == "1") {
						$("em." + string).text("*" + data.num);
						//后台返回数据，判断购物车中是否已存在，存在，不追加节点
					} else {
//后台返回数据，判断购物车中是否已存在，不存在，追加节点
						$("p.sc_goods_none").remove();//移除节点
						$("ul.sc_goods_ul").append(html);//追加节点

					}
					if (data.num == "0") { //判断数量是否为0，为0则隐藏底部查看工具
						$("div.sc_goods_foot").hide();
					}
					else {
						$("div.sc_goods_foot").show();
					}
					$('#totalnum').text(data.sum);
					$('#fee').text(data.fee);
					$('#showIncludeCart').modal();
				},
				error: function (XMLHttpRequest, ajaxOptions, thrownError) {
					alert(XMLHttpRequest + thrownError);
				}
			})
		}
		//增加数量
		function add() {
			var stock = parseInt($("#stock").html());
			var num = $(".goodnum").val();
			num++;
			if (num <= stock) {
				$(".goodnum").val(num);
			}
		}
		//减少数量
		function reduce() {

			var num = $(".goodnum").val();

			if (num > 1) {
				num--;
				$(".goodnum").val(num);
			}
			else {
				$("#oneA").addClass("important");
				alert("数量最少为1")
			}

		}
		//登录后刷新页面，载入数据
		$("#login_btn").click(function () {

			var yourname = $('#inputusername').val();
			var yourword = $('#inputpassword').val();

			$.ajax({
				type: 'post', //传送的方式,get/post
				url: '<?php echo U("User/loginfromdialog");?>', //发送数据的地址
				data: {username: yourname, password: yourword},
				dataType: "json",
				success: function (data) {
					if (data.status == "1") {
						$(".tips").html(data.info);
						document.orderform.submit();
					}
					else {
						$(".tips").html(data.info);

					}

				},
				error: function (event, XMLHttpRequest, ajaxOptions, thrownError) {
					alert(XMLHttpRequest + thrownError);
				}
			});
		});

		$("form#comment_form").submit(function () {
			var text = $("form#comment_form textarea").val();
			if (text.length <= 5) {
				topAlert("请尽量完整描述您的问题");
				return false;
			}

			$.post("<?php echo U('GoodsComments/add');?>", {
				"goods_id": gooid,
				'content': text,
			}, function (data) {
				topAlert(data.info, data.status);
				if (data.url) {
					setTimeout(function () {
						window.location.href = data.url;
					}, 3000);
				}
			}, 'json');
			return false;
		});
	</script>


</body>
</html>