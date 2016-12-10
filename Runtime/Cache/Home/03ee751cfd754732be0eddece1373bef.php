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
	
	<link type="text/css" href="/Public/Home/css/selectpick.css" rel="stylesheet"/>
	<link type="text/css" href="/Public/Home/css/city.css" rel="stylesheet"/>

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

	<div class="am-g am-container am-margin-top-xl">
		<div id="check" class="am-panel am-panel-secondary">
			<div class="am-panel-hd">
				<h2 class="am-margin-0">填写并核对订单信息</h2>
			</div>
			<div class="orderplace am-panel-bd">
				<div class="o_title ">
					<h2>收货人信息
					</h2>
				</div>
				<div class="place">
					<?php if(empty($address)): ?><div id="senderdetail"></div>
						<div id="formsender" style="position:relative;">
							<form id="formincart" name="form" class="am-form am-form-inline">
								<div class="">
									<div class="am-u-sm-2" style="line-height: 36px"><label>所在地区:</label></div>
									<div class="am-u-sm-10">
										<div class=" infolist ">
											<div class="liststyle">
				                                <span id="Province">
				                                    <i>请选择省份</i>
				                                    <ul>
					                                    <li><a href="javascript:void(0)" alt="请选择省份">请选择省份</a></li>
				                                    </ul>
				                                    <input id="idprovince" type="hidden" name="cho_Province" value="请选择省份">
				                                </span>
				                                <span id="City">
				                                    <i>请选择城市</i>
				                                    <ul>
					                                    <li><a href="javascript:void(0)" alt="请选择城市">请选择城市</a></li>
				                                    </ul>
				                                    <input type="hidden" id="idcity" name="cho_City" value="请选择城市">
				                                </span>
				                                <span id="Area">
				                                    <i>请选择地区</i>
				                                    <ul>
					                                    <li><a href="javascript:void(0)" alt="请选择地区">请选择地区</a></li>
				                                    </ul>
				                                    <input type="hidden" id="idarea" name="cho_Area" value="请选择地区">
				                                </span>
											</div>
										</div>
									</div>
								</div>
								<div class="am-u-sm-2 am-margin-top-sm">
									<label>详细地址：</label>
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<input type="text" class="cart_long" id="address" maxlength="40" data-input="text" value="" name="area" placeholder="详细地址不能为空">
								</div>
								<div class="am-u-sm-2 am-margin-top-sm">
									<label>收 货 人：</label>
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<input type="text" class="cart_long"  style="width: 200px;" id="realname" maxlength="20" data-input="text" value=""><font class="ml10 cleb6100" style="display: none;">收货人不能为空</font>
								</div>
								<div class="am-u-sm-2 am-margin-top-sm">
									<label>手&nbsp;&nbsp;&nbsp;&nbsp;机：</label>
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<input type="text" class="cart_long" style="width: 200px;" id="phone" maxlength="11" data-msg="收货手机号码格式不正确" data-input="text" data-type="cellphone" value="">&nbsp;用于接收发货通知及送货前确认
								</div>
								<div class="am-u-sm-2 am-margin-top-sm">
									<span class="am-margin-left"><input id="isdefault" checked="checked" name="default" type="checkbox" class="cart_n_box"> 设为默认地址</span>
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<a href="javascript:void(0)" class="ncart_btn_on am-btn am-btn-primary" onclick="savemsg();">保存</a>
								</div>
								<div class="am-cf"></div>
							</form>
						</div>
						<?php else: ?>
						<div id="senderdetail">
							<p id="f_default">
								<?php if(is_array($address)): $i = 0; $__LIST__ = $address;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$a): $mod = ($i % 2 );++$i;?><input type="radio" <?php if(($a["id"]) == $default_id): ?>id="default"<?php endif; ?> name="sender" checked="checked" value="<?php echo ($a["id"]); ?>"/>&nbsp;收件人：<?php echo ($a["realname"]); ?>&nbsp;&nbsp;联系电话:<?php echo ($a["cellphone"]); ?>&nbsp;&nbsp;收货地址：<?php echo ($a["address"]); ?><br/><?php endforeach; endif; else: echo "" ;endif; ?>
							</p>
						</div><?php endif; ?>

				</div>
			</div>
		</div>
			<!--收货信息 结束-->
			<!--订单支付 开始-->
			<form action='<?php echo U("order/save");?>' method="post" name="myform" id="myform">
				<div class="orderplace am-panel am-panel-secondary">
					<div class="am-panel-hd">
						<h2 class="am-margin-0">支付</h2>
					</div>
					<div class="am-padding-lg">
						<dl>
							<dt><h2>支付方式：</h2></dt>
							<dd><input type="hidden" name="tag" id="orderid" value="<?php echo ($tag); ?>">
								<input type="hidden" name="sender" id="senderid">

								<!--<input type="radio" name="PayType" id="yue" checked="checked" value="1"> 余额支付-->
								<input type="radio" name="PayType" id="pay" checked="checked" value="2"> 在线支付(支持余额和支付宝支付)
							</dd>
						</dl>
					</div>
				</div>
				<!--订单支付 结束-->


				<!--商品信息 开始-->

				<div class="orderplace am-panel am-panel-secondary">
					<div class="am-panel-hd">
						<h2 class="am-margin-0">商品信息</h2>
					</div>
					<div class="am-padding-lg">
						<table border="0" cellspacing="0" cellpadding="0" class="gridtable cart-2" width="100%">
							<tbody>
							<tr class="bg-f5">
								<th class="am-padding-sm">商品名称</th>
								<th class="am-padding-sm">规格</th>
								<th class="am-padding-sm">单价(元)</th>
								<th class="am-padding-sm">数量</th>
							</tr>
							<?php if(is_array($shoplist)): $i = 0; $__LIST__ = $shoplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
									<td class="am-padding-sm"><A href="<?php echo U('Article/detail?id='.$vo['goodid']);?>"> <?php echo (get_good_name($vo["goodid"])); ?></A>
									</td>
									<td class="am-padding-sm"><span class="weight"><?php echo ((isset($vo["parameters"]) && ($vo["parameters"] !== ""))?($vo["parameters"]):"无"); ?></span></td>
									<td class="am-padding-sm am-text-warning"><?php echo ($vo["price"]); ?></td>
									<td class="am-padding-sm"><?php echo ($vo["num"]); ?></td>

								</tr><?php endforeach; endif; else: echo "" ;endif; ?>
							</tbody>
						</table>
					</div>
				</div>
				<!--商品信息 结束-->
				<!--优惠券开始-->
				<div class="orderplace am-panel am-panel-secondary">
					<div class="am-panel-hd">
						<h2 class="am-margin-0">选择可用优惠券</h2>
					</div>
					<div  class="am-padding-lg am-panel-bd">
						<select name="coupon_id" id="coupon_id" data-am-selected>
							<option value="0" data-lowpayment="0" data-price="0" data-type="<?php echo ($c["type"]); ?>">不使用</option>
							<?php if(is_array($coupons)): $i = 0; $__LIST__ = $coupons;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i; if(($c["type"]) == "1"): ?><option value="<?php echo ($c["id"]); ?>"  data-lowpayment="<?php echo ($c["lowpayment"]); ?>" data-price="<?php echo ($c["price"]); ?>" data-type="<?php echo ($c["type"]); ?>"><?php echo ($c["title"]); ?>-<?php echo ($c['price']*10); ?>折</option>
									<?php else: ?>
									<option value="<?php echo ($c["id"]); ?>"  data-lowpayment="<?php echo ($c["lowpayment"]); ?>" data-price="<?php echo ($c["price"]); ?>" data-type="<?php echo ($c["type"]); ?>"><?php echo ($c["title"]); ?>-优惠¥<?php echo ($c["price"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</div>
				</div>         <!--优惠券结束-->

				<div class="orderplace am-panel am-panel-secondary">
					<div class="am-panel-hd">
						<h2 class="am-margin-0">订单备注</h2>
					</div>
					<div  class="am-padding-lg am-panel-bd">
						<textarea class="am-input-sm" name="message" id="" cols="30" style="width: 100%" rows="3"></textarea>
					</div>
				</div>


				<!--提交信息 开始-->
				<div class="orderplace trans am-text-right am-margin-bottom-xl">
					<p><b><?php echo ($num); ?></b>件商品</p>

					<p>商品金额<b><?php echo ($total); ?></b>元 </p>

					<p>运费<b><?php echo ($trans); ?></b>元</p>

					<?php if(($score) > "0"): ?><p>
							<label><input type="checkbox" name="score" id="huo" value="<?php echo ($score); ?>"> <strong class="am-text-warning">积分<?php echo ($score); ?>，可兑换成<?php echo ($ratio); ?>元</strong></label>
						</p><?php endif; ?>


					<p class="jiesuan">应付总额 <span id="TotalNeedPay" class=" am-text-xl am-text-warning num-text">￥<?php echo ($all); ?></span>元
						<br><span class="am-text-xs">可获得 <span id="get_score"><?php echo ($all*C('RATIO2')); ?></span>积分</span>
						<a class="btn_submit_pay am-btn am-btn-primary am-btn-lg am-padding-left-xl am-padding-right-xl" onclick="makeorder();return false">提交订单</a>
					</p> <!--提交信息 结束-->
				</div>
			</form>




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

	<script type="text/javascript" src="/Public/Home/js/city.js"></script>
	<script type="text/javascript">
		var TotalNeedPay=parseFloat("<?php echo ($all); ?>");
		var ratio2=parseFloat("<?php echo C('RATIO2');?>");
		$(document).ready(function () {

			$("#show").click(function () {
				$("#formsender").toggle();
			});

			$("select#coupon_id").change(function(){
				var type=$("select#coupon_id option:selected").data("type");
				if(type==1){
					var discount=$("select#coupon_id option:selected").data("price");
					var res=parseFloat(TotalNeedPay)*parseFloat(discount);
					if(res<=0){
						res=0.01;
					}
					$("#TotalNeedPay").html("￥"+res);
				}else{
					var price=$("select#coupon_id option:selected").data("price");
					var res=parseFloat(TotalNeedPay)-parseFloat(price);
					if(res<=0){
						res=0.01;
					}
					$("#TotalNeedPay").html("￥"+res);
					$("#get_score").html(Math.floor(res*ratio2));
				}
			});
		});

		$("#huo").change(function(){
			var radio="<?php echo ($ratio); ?>";
			if(this.checked){
				$("#TotalNeedPay").html("￥"+(parseFloat($("#TotalNeedPay").html().replace("￥",''))-parseFloat(radio)));
				TotalNeedPay=TotalNeedPay-parseFloat(radio);
			}else{
				$("#TotalNeedPay").html("￥"+(parseFloat($("#TotalNeedPay").html().replace("￥",''))+parseFloat(radio)));
				TotalNeedPay=TotalNeedPay+parseFloat(radio);
			}
			$("#get_score").html(Math.floor(TotalNeedPay*ratio2));

		});

		function savemsg() {
//判断是否是默认地址
			if (document.getElementById("isdefault").checked == true) {
				var info = "yes";
			}
			else {
				var info = "no";
			}
			var pca = $("#idprovince").val() + $("#idcity").val() + $("#idarea").val();
			var address = $("#address").val();
			var orderid = $("#orderid").val();
			var username = $("#realname").val();
			var phone = $("#phone").val();
			var b = $("#youbian").val();

			$.ajax({
				type: 'post', //传送的方式,get/post
				url: '<?php echo U("Address/build");?>', //发送数据的地址
				data: {
					province: $("#idprovince").val(),
					city: $("#idcity").val(),
					area: $("#idarea").val(),
					posi: address,
					pho: phone,
					rel: username,
					id: orderid,
					msg: info
				},
				dataType: "json",
				success: function (data) {
					if (data.msg == "yes") {
						$("#f_default").remove();
						var str = '<p id="f_default"><input type="radio" name="sender" value="' + data.addressid + '" id="default" checked="checked"/>&nbsp;&nbsp;收件人：' + data.realname + '&nbsp;&nbsp;&nbsp;联系电话：' + data.cellphone + '&nbsp;' + '&nbsp;&nbsp;&nbsp;收货地址：' + data.province + data.city + data.area + data.address + '</p>';
						$("#senderdetail").append(str);
					}
					else {
						var str = '<p><input type="radio" id="new" name="sender" value="' + data.addressid + '" checked="checked"/>&nbsp;&nbsp;收件人：' + data.realname + '&nbsp;&nbsp;&nbsp;联系电话：' + data.province + data.city + data.area + data.cellphone + '&nbsp;' + '&nbsp;&nbsp;&nbsp;收货地址：' + data.address + '</p>';
						$("#senderdetail").append(str);
					}

					$("#formsender").toggle();
				},
				error: function (event, XMLHttpRequest, ajaxOptions, thrownError) {
					alert("表单错误，" + XMLHttpRequest + thrownError);
				}
			})
		}
		function makeorder() {
			//判断默认地址是否选中,获取地址id
			if (document.getElementById("default").checked == true) {
				document.getElementById("senderid").value = document.getElementById("default").value;
				document.myform.submit();
			}
			var val = $('input:radio[name="sender"]:checked').val();
			//判断新地址是否选中,获取地址id
			if (val == null) {

				alert("请选择一个地址!");
				return false;
			}
			else {
				document.getElementById("senderid").value = val;
				document.myform.submit();
			}
		}


		function checkcode()  //检查优惠券是否可以
		{
			var str = $("input#code").val();
			if (str !== "") {
				$.ajax({
					type: 'post',
					url: '<?php echo U("Fcoupon/check");?>', //发送数据的地址//
					data: {couponid: str},
					dataType: "json",
					success: function (data) {
						if (data.msg == "yes") {
							$("span#tips").html(data.info);
						}
						else {
							$("span#tips").empty();
							document.getElementById("code").value = "";
							$("span#tips").html(data.info);


						}
					}
				});//ajax结束

			}//if结束

		}



	</script>

</body>
</html>