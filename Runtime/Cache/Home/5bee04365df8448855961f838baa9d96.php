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
				<li class="am-active"><span class="am-icon-shopping-cart"></span> 购物车</li>
			</ol>
		</div>
		<div class="am-g am-container">
			全部商品&nbsp;&nbsp;<span id="sum" class="am-text-danger"><?php echo ($sum); ?></span>
		</div>
		<?php if(is_login()): if(empty($sqlcart)): ?><div class="am-g am-container am-margin">
					<div class="shop-cart-content am-padding-top-xl">
						<div class="am-u-sm-8 am-u-sm-centered am-padding-xl">
							<div class="am-padding-top-xl am-text-center">
								<h1 class="am-padding-top-xl"><img src="/Public/static/amaze/i/icon-shopcarts.png">
									你的购物车还是空的哦赶紧行动吧 !</h1>
								<a rel="nofollow" href="<?php echo U("Shop/index");?>" class="am-btn am-btn-warning
								am-padding-left-xl am-padding-right-xl">马上购物</a>
							</div>
						</div>
					</div>
				</div><?php endif; ?>
			<?php if(!empty($sqlcart)): ?><div class="shop-cart bg-f5 am-padding am-margin-bottom">
					<div class="am-g am-padding-xl">
						<form action='<?php echo U("order/add");?>' method="post" name="myform" id="form">
							<table id="table" class="gridtable">
								<thead>
								<tr>
									<th class="row-selected">
										<input class="checkbox check-all" checked="checked" type="checkbox"> 全选
									</th>
									<th>商品名</th>
									<th>价格</th>
									<th class="am-text-center">数量</th>
									<th>操作</th>
								</tr>
								</thead>

								<tbody>

								<?php if(is_array($sqlcart)): $i = 0; $__LIST__ = $sqlcart;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="cart_bottom">
										<td>
											<input class="ids row-selected" checked="checked" type="checkbox"
												   name="id[]"
												   value="<?php echo ($vo["goodid"]); ?>"></td>
										<td>
											<span class="c5">
												<A href="<?php echo U('Article/detail?id='.$vo['goodid']);?>" class="dl">
												<img src="<?php echo (get_cover(get_cover_id($vo["goodid"]),'path')); ?>" width="70" height="70"/></a>
										    <span class="dd"><a href="<?php echo U('Article/detail?id='.$vo['goodid']);?>" class="dd"> <?php echo (get_good_name($vo["goodid"])); ?></a></span>
										 <span class="dd"><?php echo ($vo["parameters"]); ?></span>
										  </span>
										</td>
										<td align="center">¥<?php echo ($vo["price"]); ?></td>
										<td align="center">
											<div class="quantity-form"><a rel="jia" class="jia"
																		  onclick="myfunction(event)">+</a><input
													type="text"
													class="goodnum"
													id="<?php echo ($vo["sort"]); ?>"
													name="num[]"
													value="<?php echo ($vo["num"]); ?>"/><a
													rel="jian" onclick="myfunction(event)" class="jian" id="oneA">-</a>
												<input type="hidden" value="<?php echo ($vo["price"]); ?>" name="price[]"/>
												<input type="hidden" value="<?php echo ($vo["sort"]); ?>" name="sort[]"/>
												<input type="hidden" value="<?php echo ($vo["parameters"]); ?>" name="parameters[]"/>
											</div>
										</td>

										<td><a name="<?php echo ($vo["sort"]); ?>" rel="del" onclick="myfunction(event)">删除</a>&nbsp;
											<!--<a-->
												<!--href="javascript:void(0);" onclick="favor(<?php echo ($vo["goodid"]); ?>)">移到收藏</a>-->
										</td>
									</tr><?php endforeach; endif; else: echo "" ;endif; ?>
							</table>
							<table class="cart_info">
								<td colspan="4"><input class="checkbox check-all" checked="checked" type="checkbox"> 全选 </td>
								<td align="right"></td>
								</tr>
								<tr>
									<td colspan="5" style="background:#f5f5f5;"></td>
								</tr>
								</tbody>
								<tr>
									<td><a name="<?php echo ($vo["id"]); ?>" rel="del" href='<?php echo U("index/index");?>' class="am-text-warning am-padding-left-lg am-text-lg">继续购物</a></td>
									<td colspan="4" align="right">
										种类：<span id="count"><?php echo ($count); ?></span>种&nbsp;
										总计（不含运费）：<span class="am-text-warning">¥<em class="price" id="total">
										<?php echo ($price); ?></em></span>&nbsp;&nbsp;
										<a class="btn_submit_pay am-btn am-btn-primary am-btn-lg am-padding-left-xl am-padding-right-xl" href="javascript:void(0)"
										   onclick="checklogin();return false">去结算</a>
									</td>
								</tr>
							</table>


						</form>
					</div>
				</div><?php endif; ?>
			<?php else: ?>

			<?php if(empty($usercart)): ?><div class="am-g am-container am-margin">
					<div class="shop-cart-content am-padding-top-xl">
						<div class="am-u-sm-8 am-u-sm-centered am-padding-xl">
							<div class="am-padding-top-xl am-text-center">
								<h1 class="am-padding-top-xl"><img src="/Public/static/amaze/i/icon-shopcarts.png">
									你的购物车还是空的哦赶紧行动吧 !</h1>
								<a rel="nofollow" href="<?php echo U("Shop/index");?>" class="am-btn am-btn-warning
								am-padding-left-xl am-padding-right-xl">马上购物</a>
							</div>
						</div>
					</div>
				</div><?php endif; ?>

			<?php if(!empty($usercart)): ?><div class="shop-cart bg-f5 am-padding am-margin-bottom">
					<div class="am-g am-padding-xl">
						<form action='<?php echo U("Shopcart/order");?>' method="post" name="myform" id="form">
					<table id="table" class="gridtable" width="100%">
						<thead>
						<tr>
							<th class="row-selected">
								<input class="checkbox check-all" type="checkbox" checked=""> 全选
							</th>
							<th>商品名</th>
							<th>价格</th>
							<th class="am-text-center">数量</th>
							<th>操作</th>
						</tr>
						</thead>

						<tbody>

						<?php if(is_array($usercart)): foreach($usercart as $key=>$vo): ?><tr>
								<td>
									<input class="ids row-selected" checked="" type="checkbox" name="id[]"
									       value="<?php echo ($vo["id"]); ?>"></td>
								<td> <span class="c5"><A href="<?php echo U('Article/detail?id='.$vo['id']);?>" class="dl"> <img
										src="<?php echo (get_cover(get_cover_id($vo["id"]),'path')); ?>" width="70" height="70"/></a>
		  <span class="dd"><a href="<?php echo U('Article/detail?id='.$vo['id']);?>" class="dd"> <?php echo (get_good_name($vo["id"])); ?></a></span>
		 <span class="dd"><?php echo ($vo["parameters"]); ?></span>
		  </span></td>

								<td align="center"><?php echo ($vo["price"]); ?>元</td>
								<td align="center">
									<div class="quantity-form">
										<a rel="jia" class="jia" onclick="myfunction(event)">+</a>
										<input type="text" class="goodnum am-input-sm" id="<?php echo ($vo["sort"]); ?>" name="num[]" value="<?php echo ($vo["num"]); ?>"/>
										<a rel="jian" onclick="myfunction(event)" class="jian" id="oneA">-</a>
										<input type="hidden" value="<?php echo ($vo["price"]); ?>" name="price[]"/>
										<input type="hidden" value="<?php echo ($vo["sort"]); ?>" name="sort[]"/>
										<input type="hidden" value="<?php echo ($vo["parameters"]); ?>" name="parameters[]"/>
									</div>
								</td>

								<td><a name="<?php echo ($vo["sort"]); ?>" rel="del" onclick="myfunction(event)">删除</a>&nbsp;

									<!--<a-->
										<!--href="javascript:vod(0);" onclick="favor(<?php echo ($vo["id"]); ?>)">移到收藏</a>-->

								</td>
							</tr><?php endforeach; endif; ?>
						<tr>
							<td colspan="4"><input class="checkbox check-all" checked="" type="checkbox"> 全选 </td>
							<td align="right"></td>
						</tr>
						<tr>
							<td colspan="5" style="background: #f5f5f5;"></td>
						<tr>
							<td colspan="5" align="right">种类：<span id="count"><?php echo ($count); ?></span>种 &nbsp;金额小计：<span id="total">￥<?php echo ($price); ?></span>元</td>
						</tr>
						<tr>
							<td><a name="<?php echo ($vo["id"]); ?>" rel="del" href="<?php echo U(" index/index");?>"  class="am-text-warning am-padding-left-lg am-text-lg">继续购物</a></td>
							<td colspan="4" align="right">
								总计（不含运费）：<em class="price am-text-warning" id="total">￥<?php echo ($price); ?></em> 元 &nbsp;&nbsp;
								<a class="btn_submit_pay am-btn am-btn-primary am-btn-lg am-padding-left-xl am-padding-right-xl"  href="javascript:void(0)" onclick="checklogin();return false">去结算</a>
							</td>
						<tr>
						</tbody>
					</table>
					<input type="hidden" value="<?php echo ($uid); ?>" id="uid"/>

				</form>
					</div>
				</div><?php endif; endif; ?>
	</div>
	<!-- jQuery 遮罩层 begin -->
	<div id="fullbg"></div>
	<!-- end jQuery 遮罩层 -->

	<!-- jQuery 遮罩层上方的对话框 -->
	<script type="text/javascript">
		//显示灰色 jQuery 遮罩层
		function showBg() {
			var bh = $("body").height();
			var bw = $("body").width();
			$("#fullbg").css({
				height: bh,
				width: bw,
				display: "block"
			});
			$("#dialog").show();
		}
		//关闭灰色 jQuery 遮罩
		function closeBg() {
			$("#fullbg,#dialog").hide();
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
						window.location.reload();
						$("#uid").val(data.uid);
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
		var uexist = "<?php echo get_username();?>";
		//全选的实现 定义加、减、删的发送路径
		if (uexist) {
			var inc = '<?php echo U("Shopcart/incNumByuid");?>';
			var dec = '<?php echo U("Shopcart/decNumByuid");?>';
			var del = '<?php echo U("Shopcart/delItemByuid");?>';

		}
		else {
			var inc = '<?php echo U("Shopcart/incNum");?>';
			var dec = '<?php echo U("Shopcart/decNum");?>';
			var del = '<?php echo U("Shopcart/delItem");?>';


		}

		function checklogin() {
			var uexist = "<?php echo get_username();?>";

			if (uexist) {
				document.myform.submit();
			}
			else {
				alert("请先登录!");
				location.href="./index.php?s=/Home/User/login.html";
			}

		}
		function favor(id) {
			var uexist = "<?php echo get_username();?>";
			if (uexist) {
				var favorid = id;
				$.ajax({
					type: 'post', //传送的方式,get/post
					url: '<?php echo U("User/favor");?>', //发送数据的地址
					data: {id: favorid},
					dataType: "json",
					success: function (data) {
						if (data.status == "1") {
							alert(data.msg);
						}
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

		function myfunction(event) {
			event = event ? event : window.event;
			var obj = event.srcElement ? event.srcElement : event.target;
//这时obj就是触发事件的对象，可以使用它的各个属性 
//还可以将obj转换成jquery对象，方便选用其他元素 
			str = obj.innerHTML.replace(/<\/?[^>]*>/g, ''); //去除HTML tag

			var $obj = $(obj);


			if (obj.rel == "jia") {
				var num = $obj.next().val();

				var gid = $obj.next().attr("id");

				a = parseInt(num) + 1;
				$obj.next().val(a);
//增加数据
				$.ajax({
					type: 'post', //传送的方式,get/post
					url: inc, //发送数据的地址
					data: {sort: gid},
					dataType: "json",
					success: function (data) {
						$("span#count").text(data.count);
						$("span#total").text(data.price);
						$("span#sum").text(data.sum);
						$("em.price").text(data.price);

					},
					error: function (event, XMLHttpRequest, ajaxOptions, thrownError) {
						alert(XMLHttpRequest + thrownError);
					}
				})
			}

			if (obj.rel == "jian") {
				var num = $obj.prev().val();

				var str = $obj.prev().attr("id")


				//如果文本框的值大于0才执行减去方法
				if (num > 0) {
					//并且当文本框的值为1的时候，减去后文本框直接清空值，不显示0
					if (num == 1) {
						alert("最少为1");
						return true;
					}
					//否则就执行减减方法
					else {
						a = parseInt(num) - 1;

						$obj.prev().val(a);

					}


				}


//减少数据
				$.ajax({
					type: 'post', //传送的方式,get/post
					url: dec, //发送数据的地址
					data: {sort: str},
					dataType: "json",
					success: function (data) {
						$("span#count").text(data.count);
						$("span#total").text(data.price);
						$("span#sum").text(data.sum);
						$("em.price").text(data.price).fadeIn();

					},
					error: function (event, XMLHttpRequest, ajaxOptions, thrownError) {
						alert(XMLHttpRequest + thrownError);
					}
				})
			}
			var html = "<div class='am-u-sm-8 am-u-sm-centered am-padding-xl'><div class='am-padding-top-xl am-text-center'><h1 class='am-padding-top-xl'><img src='/Public/static/amaze/i/icon-shopcarts.png'> 你的购物车还是空的哦赶紧行动吧!</h1><a rel='nofollow' href='<?php echo U("Shop/index");?>' class='am-btn am-btn-warning	am-padding-left-xl am-padding-right-xl'>马上购物</a></div></div>";
			if (obj.rel == "del") {
				var string = obj.name;
//删除数据
				$.ajax({
					type: 'post', //传送的方式,get/post
					url: del, //发送数据的地址
					data: {sort: string},
					dataType: "json",
					success: function (data) {
						var $obj = $(obj);
						$obj.parents("tr").empty();
						$("span#count").text(data.count);
						$("span#total").text(data.price);
						$("span#sum").text(data.sum);
						$("em.price").text(data.price);
						var a = data.sum;
						if (a == "0") {
							$(".text").remove();
							$("#form").html(html);
						}
					},
					error: function (event, XMLHttpRequest, ajaxOptions, thrownError) {
						alert(XMLHttpRequest + thrownError);
					}
				})
			}


		}


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