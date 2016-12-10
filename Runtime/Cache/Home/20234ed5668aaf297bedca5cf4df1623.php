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
	
	<title><?php echo ($cache["xl_name"]); ?></title>
	<!--<link rel="stylesheet" href="/Public/Home/css/amazeui.min.css">-->
	<link rel="stylesheet" href="/Public/Home/css/jquery.datetimepicker.css">
	<link rel="stylesheet" href="/Public/Home/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="/Public/Home/css/zoom.css">

	<link rel="stylesheet" href="/Public/Home/css/style.css">
	<style>
		.navfixed{position: fixed;top: 50px;z-index: 99999;max-width: 1000px;}
		.xdsoft_datetimepicker .xdsoft_calendar td, .xdsoft_datetimepicker .xdsoft_calendar th{padding:10px 15px;}
	</style>
	<!--[if lt IE 9]>
		<link rel="stylesheet" href="../css/style1-IE.css" />
		<script type="text/javascript" src="../js/jquery-1.11.0.js" ></script>
		<script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  		<script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
		<script type="text/javascript" src="../js/amazeui.ie8polyfill.min.js" ></script>
	<![endif]-->

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

	<style type="text/css">
		.date_box{height:auto;}
	</style>
		<!--section-->
		<div class="my_main">
			<input type="hidden" id="priceid" />
			<input type="hidden" id="goodsimg" value="<?php echo ($cache["xl_list_img"]); ?>" />
			<input type="hidden" id="goodsname" value="<?php echo ($cache["xl_name"]); ?>" />
			<div class="am-g am-g-fixed am-g-collapse">
				<?php if(is_array($lineinfo)): $k = 0; $__LIST__ = $lineinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><input type="hidden" class="lineinfo" name="startdate" data-price='<?php echo ($vo[5]); ?>' data-end='<?php echo ($vo[3]); ?>' data-week='<?php echo ($vo[4]); ?>' data-pid='<?php echo ($vo[1]); ?>' value="<?php echo ($vo[2]); ?>" data-setname="<?php echo ($vo[6]); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
				<!--面包屑导航-->
				<div class="am-breadcrumb am-breadcrumb-slash" style="margin-top: 60px;">
					<li><a href="/">首页</a></li>
					<li><a href="<?php echo U('Shop/index');?>">健康商城</a></li>
					<li><a href="<?php echo U('Hotel/index');?>">酒店预订</a></li>
					<li class="am-active"><a href="#"><?php echo ($cache["xl_name"]); ?></a></li>
				</div>

				<!--酒店简介-->
				<div class="am-u-lg-12 hotel_abstract">
					<h1 class="am-u-lg-8 hotel_name"><?php echo ($cache["xl_name"]); ?></h1>
					<!--收藏与分享-->
					<div class="am-u-lg-4 am-pagination-right">
						<div class="am-u-lg-6">
							<a href="javascript:void(0);" onclick="favor();return false;"><img class="am-fr" src="/Public/Home/img/sc_icon.png"></a>
							<span class="am-fr">收藏产品：</span>
						</div>
						<div class="am-u-lg-6">
							<a target="_blank" title="分享到人人网" href="http://widget.renren.com/dialog/share?resourceUrl=http://<?php echo ($_SERVER['HTTP_HOST']); echo U('Hotel/detail?id='.$cache['xl_id']);?>;srcUrl=http://<?php echo ($_SERVER['HTTP_HOST']); ?>&amp;title=<?php echo ($cache["xl_name"]); ?>。（来自<?php echo C('SITENAME');?>）&amp;pic=<?php echo C('DOMAIN'); echo ($cache["xl_list_img"]); ?>" data-item="renren" class="J_vivo_share"><img alt="人人网" class="am-fr" src="/Public/Home/img/fx_rr.png"></a>
							<a title="分享到腾讯微博" target="_blank" href="http://v.t.qq.com/share/share.php?title=<?php echo ($cache["xl_name"]); ?>（来自http://<?php echo ($_SERVER['HTTP_HOST']); ?>）。&amp;url=http://<?php echo ($_SERVER['HTTP_HOST']); echo U('Hotel/detail?id='.$cache['xl_id']);?>&amp;pic=<?php echo ($cache["xl_list_img"]); ?>" data-item="tencent" class="J_vivo_share"><img alt="腾讯微博" class="am-fr" src="/Public/Home/img/fx_txweibo.png">
							<a title="分享到新浪微博" target="_blank" href="http://service.t.sina.com.cn/share/share.php?title=<?php echo ($cache["xl_name"]); ?>。（来自http://<?php echo ($_SERVER['HTTP_HOST']); ?>）&amp;url=http://<?php echo ($_SERVER['HTTP_HOST']); echo U('Hotel/detail?id='.$cache['xl_id']);?>&amp;pic=<?php echo ($cache["xl_list_img"]); ?>" data-item="sina" class="J_vivo_share"><img alt="新浪微博" class="am-fr" src="/Public/Home/img/fx_sina.png"></a>
							<span class="am-fr">分享到：</span>
						</div>
					</div>
					<!--地址-->
					<p class="am-u-lg-12">
						<?php echo ($cache["xl_cf_city"]); ?>-<?php echo ($cache["xl_jd_address"]); ?>
					</p>
					<!--简介详情-->
					<div class="am-u-lg-12 hotel_abstract_dt">
						<?php echo ($cache["xl_fb_detail"]); ?>
						<br />
						<?php echo ($cache["xl_jd_jianjie"]); ?>
					</div>
					<div class="am-u-lg-12" style="margin-top: 15px;">
						<div class="am-u-lg-9 am-pagination-left line_price"><span class="s_price" style="font-size: 5.5rem;font-weight: normal;"><?php echo ($cache["xl_qijia"]); ?></span><span style="font-size: 1.8rem;"><?php echo ($cache["xl_qijia_dw"]); ?></span></div>
					</div>
				</div>

				<!--酒店相册，超出5张自动隐藏，但相册打开后能显示全部-->
				<div class="am-u-lg-12 hotel_album_box gallery clear">
					<!--demo start-->
					<!-- <div class="img_bx">
						<a href="<?php echo ($cache["xl_list_img"]); ?>">
							<img class="img_album" src="<?php echo ($cache["xl_list_img"]); ?>">
						</a>
					</div> -->
					<!--demo end-->
					<?php if(is_array($cache["xl_list_img_arr"])): $i = 0; $__LIST__ = $cache["xl_list_img_arr"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="img_bx">
						<a href="<?php echo ($vo); ?>">
							<img class="img_album" src="<?php echo ($vo); ?>">
						</a>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
				</div>

				<!--全部跳转按钮-->
				<div class="am-u-lg-12 am-avg-lg-5 am-thumbnails to_btn" id="linenav">
					<li class="active"><a href="#tcbh">套餐包含</a></li>
					<li><a href="#fwss">服务设施</a></li>
					<li><a href="#ydxz">预订须知</a></li>
					<!--<li><a href="#pj">评价<span>0</span>条</a></li>-->
					<li><a href="#liyd">立即预订</a></li>
				</div>

				<!--套餐包含-->
				<div id="tcbh" class="am-u-lg-12 xcap_box">
					<div class="am-u-lg-12 hotel_tcbh">
						<div>
							<p class="hotel_type">套餐包含：</p>
							<?php $_result=explode(PHP_EOL, $cache['xl_bh']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p><?php echo ($vo); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
						<?php if($cache['xl_bbh']): ?><div>
							<p class="hotel_type">套餐不包含：</p>
							<?php $_result=explode(PHP_EOL, $cache['xl_bbh']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p><?php echo ($vo); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
						</div><?php endif; ?>
					</div>
				</div>

				<!--服务设施-->
				<h1 id="fwss" class="am-u-lg-12 title">服务设施</h1>
				<div class="am-u-lg-12 xcap_box">
					<div class="fwss_title">通用设施</div>
					<div class="fwss_list clear">
						<?php $_result=explode(',', $cache['xl_tyss']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span><img src="/Public/Home/img/fwss_icon.jpg"><?php echo ($vo); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
					<div class="fwss_title">活动设施</div>
					<div class="fwss_list clear">
						<?php $_result=explode(',', $cache['xl_hdss']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span><img src="/Public/Home/img/fwss_icon.jpg"><?php echo ($vo); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
					<div class="fwss_title">服务项目</div>
					<div class="fwss_list clear">
						<?php $_result=explode(',', $cache['xl_fuxm']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span><img src="/Public/Home/img/fwss_icon.jpg"><?php echo ($vo); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
					<div class="fwss_title">客房设施</div>
					<div class="fwss_list clear">
						<?php $_result=explode(',', $cache['xl_kfss']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span><img src="/Public/Home/img/fwss_icon.jpg"><?php echo ($vo); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
				</div>

				<!--预订须知-->
				<h1 id="ydxz" class="am-u-lg-12 title">预订须知</h1>
				<div class="am-u-lg-12 xcap_box">
					<div class="am-u-lg-12 ydxz_box">
						<?php if($cache['xl_ydts']): ?><h3 class="title18 am-text-center">酒店产品预订须知</h3>
						<p>
							<?php echo str_replace(PHP_EOL,'<br/>',$cache['xl_ydts']);?>
						</p><?php endif; ?>
						<?php if($cache['xl_tkzc']): ?><h3 class="title18 am-text-center">酒店产品退改政策</h3>
						<p>
							<?php echo str_replace(PHP_EOL,'<br/>',$cache['xl_tkzc']);?>
						</p><?php endif; ?>
					</div>
				</div>

				<!--选择日期与人数-->
				<h1 id="liyd" class="am-u-lg-12 title">选择日期及人数</h1>
				<div class="am-u-lg-12 xcap_box">
					<div class="am-u-lg-8 date_box">
						<input id="pickDateBtn" type="text" style="display: none;" value="<?php echo date('Y-m-d');?>">
					</div>
					<div class="am-u-lg-4 xz_rs">
						<div class="am-u-lg-12 pull_hight">
							<div class="am-u-lg-9 am-pagination-right line_price s_price"><?php echo ($cache["xl_qijia"]); ?></div>
							<div class="am-u-lg-3 line_dw">
								<p><?php echo ($cache["xl_qijia_dw"]); ?></p>
							</div>

							<span class="am-u-lg-12 am-pagination-right am-padding-left-sm am-p">选择数量</span>

							<div class="am-u-lg-12  am-pagination-right am-padding-left-sm  clear">
								<div class="number-group am-fr  clear">
									<input class="jiannum" type="button" value="-">
									<input class="numval" type="text" value="1">
									<input class="jianum" type="button" value="+">
								</div>
							</div>

							<div class="am-u-lg-12 yd_gp">
								<div class="am-u-lg-7">
									<p class="" style="color:orange">咨询电话</p>
									<p style="color:orange">4000096828</p>
								</div>
								<div class="am-u-lg-5">
									<button class="am-btn am-btn-warning am-btn-xl am-radius" onclick="ydhotel()">立即预订</button>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<!--预定选择类型模态框-->

		<div class="model">
			<div class="model_cnt">
				<span class="close_btn">X</span>
				<h1 class="title18 am-text-center">选择套餐</h1>
				<p class="rztime">2016年6月30日入住</p>
				<div class="hotel_type_box am-g">
					<?php if(is_array($lineinfo)): $k = 0; $__LIST__ = $lineinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><!--套餐row-->
						<div class="am-u-lg-12 hotel_type_row" data-start="<?php echo ($vo[2]); ?>" data-end="<?php echo ($vo[3]); ?>" data-week="<?php echo ($vo[4]); ?>" style="display:none;">
							<div class="am-u-lg-2">
								<input type="radio" name="hotel-t" data-price="<?php echo ($vo[5]); ?>" data-pid='<?php echo ($vo[1]); ?>' data-setname="<?php echo ($vo[6]); ?>">
								<!-- <input type="hidden" class="lineinfo" name="startdate" data-price='' data-end='' data-week='' data-pid='<?php echo ($vo[1]); ?>' value="" data-setname="<?php echo ($vo[6]); ?>"/> -->
							</div>
							<div class="am-u-lg-6">
								<h2 class="title16" title="<?php echo ($vo[6]); ?>" style="height:4rem;overflow:hidden"><?php echo ($vo[6]); ?></h2>
							</div>
							<div class="am-u-lg-4 ht_prs">
								￥<span><?php echo ($vo[5]); ?></span>
							</div>
						</div><?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
				<p class="am-text-center">
					<button class="am-btn am-btn-primary" onclick="confirmSet(this)">确认提交</button>
					<button class="am-btn am-btn-default" onclick="closemodel();">关闭</button>
				</p>
			</div>
		</div>
		

		<script type="text/javascript" src="/Public/Home/js/jquery-2.1.0.js"></script>
		<script type="text/javascript" src="/Public/Home/js/jquery.datetimepicker.full.min.js"></script>
		<script type="text/javascript" src="/Public/Home/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="/Public/Home/js/zoom.js"></script><div id="zoom" style="display: none;"><a class="close"></a><a href="#previous" class="previous"></a><a href="#next" class="next"></a><div class="content loading"></div></div>
		<script type="text/javascript">
			$(function(){
				var pp = $(".lineinfo");

				var maxDate, minDate, tmpDate1 = 0, tmpDate2 = 0;
				$.each(pp, function(i){
					var tmp1 = Date.parse($(this).data("end"));
					var tmp2 = Date.parse($(this).val());
					if(i == 0){
						tmpDate1 = tmp1;
						tmpDate2 = tmp2;
					}else{
						if(tmp1 > tmpDate1){
							tmpDate1 = tmp1;
						}
						if(tmp2 < tmpDate2){
							tmpDate2 = tmp2;
						}
					}
				})

				maxDate = new Date(tmpDate1).getFullYear() + "/" + (new Date(tmpDate1).getMonth() + 1) + "/" + new Date(tmpDate1).getDate();

				if(tmpDate2 <= Date.parse(new Date()))
				{
					tmpDate2 = new Date();
				}
				minDate = new Date(tmpDate2).getFullYear() + "/" + (new Date(tmpDate2).getMonth() + 1) + "/" + new Date(tmpDate2).getDate();

				// console.log(maxDate);
				// console.log(minDate);
				// console.log(pp);


				$.datetimepicker.setLocale('ch');
				$("#pickDateBtn").datetimepicker({
					timepicker: false,
					format: 'Y-m-d',
					minDate: minDate,
					maxDate: maxDate,
					inline: true,
					keyboardNavigation:false,
					scrollMonth:false,
					scrollTime:false,
					scrollInput:false,
					//				opened:true,
					onChangeDateTime: function() {},
					onSelectDate: function(){
						var dateinput1 = $('#pickDateBtn').val();
						var dateinput = Date.parse(dateinput1);
						dateweek = new Date(dateinput);
						dateweek = dateweek.getDay();
						//dateweek = dateweek.toString();
						if(pp.length > 1){
							var modelList = $(".hotel_type_row");
							var tempcount = 0;
							$.each(modelList, function(){
								var tempStart = Date.parse($(this).data("start"));
								var tempEnd = Date.parse($(this).data("end"));
								var tempWeek = $(this).data("week");
								tempWeek = tempWeek.toString();
								if(tempStart <= dateinput && tempEnd >= dateinput && tempWeek.indexOf(dateweek) >= 0){
									$(this).show();
									tempcount++;
								}
							})
							if(tempcount > 1){
								$(".rztime").html(dateinput1 + "入住");
								$(".model").show();
							}else{
								for(var i = 0; i < pp.length; i++)
								{
									var max = Date.parse(pp.eq(i).data("end"));
									var min = Date.parse(pp.eq(i).val());
									if(min <= dateinput && max >= dateinput){
										var week = pp.eq(i).data("week");
										week = week.toString();
										if(week.indexOf(dateweek)>=0){
											$('#priceid').val(pp.eq(i).data("pid"));
											$('.s_price').text(pp.eq(i).data("price"));
										}
										closemodel();
									}else{
										return false;
									}
								}
							}
						}else{
							for(var i = 0; i < pp.length; i++)
							{
								var max = Date.parse(pp.eq(i).data("end"));
								var min = Date.parse(pp.eq(i).val());
								if(min <= dateinput && max >= dateinput){
									var week = pp.eq(i).data("week");
									week = week.toString();
									if(week.indexOf(dateweek)>=0){
										$('#priceid').val(pp.eq(i).data("pid"));
										$('.s_price').text(pp.eq(i).data("price"));
									}
								}else{
									return false;
								}
							}
						}
					},
					onGenerate: function(){
						var td = $("td");
						$.each(td, function(){
							if(!$(this).hasClass("xdsoft_disabled")){
								var tdDate = $(this).data("year") + "-" + ($(this).data("month") + 1) + "-" + $(this).data("date");
								tdDate = Date.parse(tdDate);
								var _this = this;
								$.each(pp, function(){
									var max = Date.parse($(this).data("end"));
									var min = Date.parse($(this).val());
									if(min <= tdDate && max >= tdDate){
										var week = $(this).data("week");
										week = week.toString();
										var tdDateWeek = new Date(tdDate).getDay();
										if(week.indexOf(tdDateWeek) >= 0){
											if($(_this).find("div").has("span").length > 0){
												$(_this).find("div span").html($(this).data("price"));
											}else{
												$(_this).find("div").append("<br/><span style='color:red'>" + $(this).data("price") + "</span>");
											}
										}
									}
								})
							}
						})
					}
				});
				
					var dateinput1 = $('#pickDateBtn').val();
					dateinput = Date.parse(dateinput1);
					dateweek = new Date(dateinput);
					dateweek = dateweek.getDay();
					dateweek = dateweek.toString();
					$.each(pp, function(){
						var max = Date.parse($(this).data("end"));
						var min = Date.parse($(this).val());
						if(min <= dateinput && max >= dateinput){
							var week = $(this).data("week");
							week = week.toString();
							if(week.indexOf(dateweek)>=0){
								$('#priceid').val($(this).data("pid"));
								$('.s_price').text($(this).data("price"));
							}
						}
					})

				$('#pickDateBtn').change(function(){
					
				})
				
				
				$('#table-myorder2').DataTable({
					"paging": true,
					"lengthChange": false,
					"searching": false,
					"ordering": true,
					"info": false,
					"autoWidth": true,
					"ordering": false,
					"language": {
						"sLengthMenu": "每页显示 _MENU_ 条记录",
						"sZeroRecords": "抱歉， 没有找到",
						"sInfo": "从 _START_ 到 _END_ /共 _TOTAL_ 条数据",
						"sInfoEmpty": "没有数据",
						"sZeroRecords": "没有检索到数据",
						"oPaginate": {
							"sFirst": "首页",
							"sPrevious": "前一页",
							"sNext": "后一页",
							"sLast": "尾页"
						}
					}
				});
				var linenav;
				$(document).ready(function() {
					linenav = $('#linenav').offset().top;
				});
				window.onscroll = function(e) {
					var e = e || window.event;
					var scrolltop1 = document.documentElement.scrollTop || document.body.scrollTop;
					if (scrolltop1 >= linenav) {
						$("#linenav").addClass("navfixed");
					} else {
						$("#linenav").removeClass("navfixed");
					}
				}
				//数字框组加减
				$(".jiannum").on("click", function() {
					var nmval = $(this).siblings(".numval").val();
					if (nmval == 1) {} else {
						$(this).siblings(".numval").val(--nmval);
					}
				});
				$(".jianum").on("click", function() {
					var nmval = $(this).siblings(".numval").val();
					$(this).siblings(".numval").val(++nmval);
				});
				
				
				$(".close_btn").on("click",function(){
					closemodel();
				});
			

			})

			function closemodel(){
				$('.model').hide();
				$('[name=\'hotel-t\']').prop('checked', false);
				$(".hotel_type_row").hide();
			}

			//确认酒店套餐
			function confirmSet(obj){
				var input = $(obj).parents(".model").find("input:checked");

				if(input.length == 0){
					alert("请先选择套餐");
					return false;
				}

				var price = input.data("price");
				var pid = input.data("pid");
				var name = input.data("setname");

				$('#priceid').val(pid);
				$('.s_price').text(price);

				$(".xdsoft_current").find("span").html(price);

				closemodel();
			}
			
			//预定酒店
			function ydhotel(){
				if(!$('#priceid').val()){
					alert('请选择规定的出行日期');
					return false;
				}
				if(!$('#pickDateBtn').val()){
					alert('请选择出行日期');
					return false;
				}
				var price = $('.s_price').eq(0).text();
				location.href="<?php echo u('home/hotel/order');?>&id=<?php echo I('id');?>&cfrq="+$('#pickDateBtn').val()+"&num="+$('.numval').val()+"&price="+price+'&priceid='+$('#priceid').val();
			}
			// function tjholteod(){
			// 	location.href='add_order.html';
			// }
			//收藏
			function favor() { 
				// var uexist="<?php echo get_username();?>";
				// if(uexist){
					var favorid="<?php echo I('id');?>";
					var price=$('.s_price').html();
					$.ajax({
						type:'post', //传送的方式,get/post
						url:'<?php echo U("Collect/add");?>', //发送数据的地址
						data:{id:favorid,price:price,goodstype:2,goodsimg:$('#goodsimg').val(),goodsname:$('#goodsname').val()},
						 dataType: "json",
						success:function(data){
								if(data.status == 0){
									alert(data.info);
									return false;
								}
								alert(data.msg);
							}
					})	
				// }
				// else
				// {
				// 	showBg();
				// }
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
	<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1257408010'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/z_stat.php%3Fid%3D1257408010' type='text/javascript'%3E%3C/script%3E"));</script>
	

	
</div>

</body>
</html>