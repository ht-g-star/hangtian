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
	
	<title><?php echo ($line["xl_name"]); ?></title>
	<!--<link rel="stylesheet" href="/Public/Home/css/amazeui.min.css">-->
	<link rel="stylesheet" href="/Public/Home/css/jquery.datetimepicker.css">
	<link rel="stylesheet" href="/Public/Home/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="/Public/Home/css/style.css">
	<!--[if lt IE 9]>
		<link rel="stylesheet" href="../css/style1-IE.css" />
		<script type="text/javascript" src="../js/jquery-1.11.0.js" ></script>
		<script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  		<script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
		<script type="text/javascript" src="../js/amazeui.ie8polyfill.min.js" ></script>
	<![endif]-->


	<style type="text/css">
		.xc_to_btn.positionAbsolute{position:absolute;top:100px;left:0;}
		.xc_to_btn.positionFixed{position:fixed;top:100px;left:210px;}
		.navfixed{position: fixed;top: 50px;z-index: 99999;max-width: 1000px;}
		.xdsoft_datetimepicker .xdsoft_calendar td, .xdsoft_datetimepicker .xdsoft_calendar th{padding:10px 15px;}
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

<style type="text/css">
		.date_box{height:auto;}
	</style>
		<!--header-->
		<!--section-->
		<div class="my_main" style="margin-top: 60px;">
			<input type="hidden" id="goodsimg" value="<?php echo ($line["xl_list_img"]); ?>" />
			<input type="hidden" id="goodsname" value="<?php echo ($line["xl_name"]); ?>" />
			<div class="am-g am-g-fixed am-g-collapse">
				<!--面包屑导航-->
				<div class="am-breadcrumb am-breadcrumb-slash">
					<li><a href="<?php echo U('Index/index');?>">首页</a></li>
					<li><a href="<?php echo U('Shop/index');?>">健康商城</a></li>
					<li><a href="<?php echo U('Line/getlinelist');?>">旅游线路</a></li>
					<li class="am-active"><a href="<?php echo U('line/lineDetail?xl_id='.$lineid);?>"><?php echo ($line["xl_name"]); ?></a></li>
				</div>
				<!--banner大图-->
				<img class="am-u-lg-12" src="<?php echo ($line["xl_list_img"]); ?>">
				<!--tag标签 线路id 标题 简介  价格-->
				<div class="am-u-lg-12 line_info_gp">
					<div class="am-u-lg-10 line_tag">
						<?php if(is_array($biaoqin)): $i = 0; $__LIST__ = $biaoqin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span class="tag_item"><?php echo ($vo); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
					<div class="am-u-lg-2 am-pagination-right">线路编号：<?php echo ($line["xl_code"]); ?></div>
					<div class="am-u-lg-9">
						<h1 class="line_title"><?php echo ($line["xl_name"]); ?></h1>
						<p><?php echo ($line["xl_fb_detail"]); ?></p>
					</div>
					<div class="am-u-lg-3">
						<div class="am-u-lg-9 am-pagination-right line_price s_price">
							<?php echo ($line["xl_qijia"]); ?>
						</div>
						<div class="am-u-lg-3 line_dw">
							<p><?php echo ($line["xl_qijia_dw"]); ?>起</p>
						</div>
					</div>
				</div>
				<!--出发地 时间 日期  预订按钮-->
				<div class="am-u-lg-12 info_yd_row">
					<div class="am-u-lg-2">
						<label>出发地：</label>
						<span><?php echo ($line["xl_cf_city"]); ?>出发</span>
					</div>
					<div class="am-u-lg-2">
						<label>出团人数：</label>
						<span><?php echo ($line["xl_ctrs"]); ?>人起</span>
					</div>
					<div class="am-u-lg-2">
						<label>出游天数：</label>
						<span><?php echo ($line["xl_cttsws"]); ?></span>
					</div>
					<div class="am-u-lg-4" style="overflow: hidden;">
						<input type="hidden" id="priceid" />
						<label style="float: left;width: 20%;">出发日期：</label>
						<?php if(($lineinfocount) > "1"): ?><div style="float: left;width: 80%;border: none;padding-left: 0 !important;padding-top: 8px;line-height: 25px;">
								<?php if(is_array($lineinfo)): $k = 0; $__LIST__ = $lineinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><p class="pp" data-price='<?php echo ($vo[5]); ?>' data-min='<?php echo ($vo[2]); ?>' data-max='<?php echo ($vo[3]); ?>' data-pid='<?php echo ($vo[1]); ?>'><?php echo ($vo[2]); ?>~<?php echo ($vo[3]); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
							</div>
							<?php else: ?>
							<div style="float: left;width: 80%;border: none;padding-left: 0 !important;">
								<?php if(is_array($lineinfo)): $k = 0; $__LIST__ = $lineinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><p class="pp" data-price='<?php echo ($vo[5]); ?>' data-min='<?php echo ($vo[2]); ?>' data-max='<?php echo ($vo[3]); ?>' data-pid='<?php echo ($vo[1]); ?>'><?php echo ($vo[2]); ?>~<?php echo ($vo[3]); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
							</div><?php endif; ?>
						
						<!-- <a href="<?php echo U('line/lineDetail?xl_id='.$lineid);?>">更多&gt;&gt;</a> -->
					</div>
					<input type="button" class="am-btn am-u-lg-2 am-btn-warning yd_btn" value="立即预订" onclick="location.href=&#39;#yd&#39;">
				</div>
				<!--收藏分享-->
				<div class="am-u-lg-12 share_gp">
					<div class="am-u-lg-2">
						<span>收藏产品：</span><a href="javascript:vod(0);" onclick="favor();return false;"><img src="/Public/Home/images/sc_icon.png"></a>
					</div>
					<div class="am-u-lg-10">
						<span class="am-fl">分享到：</span>
						<a title="分享到新浪微博" target="_blank" href="http://service.t.sina.com.cn/share/share.php?title=<?php echo ($line["xl_name"]); ?>。（来自http://<?php echo ($_SERVER['HTTP_HOST']); ?>）&amp;url=http://<?php echo ($_SERVER['HTTP_HOST']); echo U('Hotel/detail?id='.$line['xl_id']);?>&amp;pic=<?php echo ($line["xl_list_img"]); ?>" data-item="sina" class="J_vivo_share"><img alt="新浪微博" class="am-fl" src="/Public/Home/images/fx_sina.png"></a>
						<a title="分享到腾讯微博" target="_blank" href="http://v.t.qq.com/share/share.php?title=<?php echo ($line["xl_name"]); ?>（来自http://<?php echo ($_SERVER['HTTP_HOST']); ?>）。&amp;url=http://<?php echo ($_SERVER['HTTP_HOST']); echo U('Hotel/detail?id='.$line['xl_id']);?>&amp;pic=<?php echo ($line["xl_list_img"]); ?>" data-item="tencent" class="J_vivo_share"><img alt="腾讯微博" class="am-fl" src="/Public/Home/images/fx_txweibo.png"></a>
						<a target="_blank" title="分享到人人网" href="http://widget.renren.com/dialog/share?resourceUrl=http://<?php echo ($_SERVER['HTTP_HOST']); echo U('Hotel/detail?id='.$line['xl_id']);?>;srcUrl=http://<?php echo ($_SERVER['HTTP_HOST']); ?>&amp;title=<?php echo ($line["xl_name"]); ?>。（来自<?php echo C('SITENAME');?>）&amp;pic=<?php echo ($line["xl_list_img"]); ?>" data-item="renren" class="J_vivo_share"><img alt="人人网" class="am-fl" src="/Public/Home/images/fx_rr.png"></a>
					</div>
				</div>
				<!--全部跳转按钮-->
				<div class="am-u-lg-12 am-avg-lg-6 am-thumbnails to_btn" id="linenav">
					<li class="active"><a href="<?php echo U('line/lineDetail?xl_id='.$lineid);?>#xlts">线路特色</a></li>
					<li><a href="<?php echo U('line/lineDetail?xl_id='.$lineid);?>#xcap">行程安排</a></li>
					<li><a href="<?php echo U('line/lineDetail?xl_id='.$lineid);?>#xftm">透明消费</a></li>
					<li><a href="<?php echo U('line/lineDetail?xl_id='.$lineid);?>#ydxz">预订须知</a></li>
					<!--<li><a href="<?php echo U('line/lineDetail?xl_id='.$lineid);?>#cjjl">成交记录</a></li>
					<li><a href="<?php echo U('line/lineDetail?xl_id='.$lineid);?>#pj">评价<span>0</span>条</a></li>-->
				</div>
				<!--线路特色-->
				<div id="xlts" class="am-u-lg-12  xlts_box">
					<?php echo ($line["xl_tese"]); ?>
				</div>
				<!--行程安排-->
				<h1 id="xcap" class="am-u-lg-12 title">行程安排</h1>
				<div class="am-u-lg-12 xcap_box">
					<!--行程跳转按钮-->
					<div class="xc_to_btn">
						<?php if(is_array($line["xl_xc"])): $i = 0; $__LIST__ = $line["xl_xc"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><a href="<?php echo U('line/lineDetail?xl_id='.$lineid);?>#d<?php echo ($vo1["xl_ts"]); ?>">D<?php echo ($vo1["xl_ts"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
					<!--行程细节-->
					<?php if(is_array($line["xl_xc"])): $i = 0; $__LIST__ = $line["xl_xc"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?><div id="d<?php echo ($vo2["xl_ts"]); ?>" class="am-u-lg-12 xc_group">
							<h2 class="xc_dt_title">Day<?php echo ($vo2["xl_ts"]); ?> <?php echo ($vo2["xl_xcbt"]); ?></h2>
							<div class="am-u-lg-8 rc_gp_info">
								<div class="am-u-lg-11 am-u-lg-offset-1">
									<?php echo ($vo2["xl_xcsm"]); ?>
									<?php if(!empty($vo2["xl_xct"])): ?><img src="<?php echo ($vo2["xl_xct"]); ?>"><?php endif; ?>
								</div>
							</div>
							<div class="am-u-lg-4 rc_gp">
								<div class="am-u-lg-12 rc_box">
									<div class="am-u-lg-2 ">
										<img src="/Public/Home/images/rc_cy_icon.png">
									</div>
									<h3 class="am-u-lg-10 ">餐饮</h3>
									<p class="am-u-lg-10 am-u-lg-offset-2">
										<?php if(!empty($vo2["xl_cy"])): echo ($vo2["xl_cy"]); else: ?>无<?php endif; ?>
									</p>
								</div>
	
								<div class="am-u-lg-12 rc_box">
									<div class="am-u-lg-2 ">
										<img src="/Public/Home/images/rc_zs_icon.png">
									</div>
									<h3 class="am-u-lg-10 ">住宿</h3>
									<p class="am-u-lg-10 am-u-lg-offset-2">
										<?php if(!empty($vo2["xl_zs"])): echo ($vo2["xl_zs"]); else: ?>无<?php endif; ?>
									</p>
								</div>
	
								<div class="am-u-lg-12 rc_box">
									<div class="am-u-lg-2 ">
										<img src="/Public/Home/images/rc_jt_icon.png">
									</div>
									<h3 class="am-u-lg-10 ">交通</h3>
									<p class="am-u-lg-10 am-u-lg-offset-2">
										参考航班：<?php if(!empty($vo2["xl_ckhb"])): echo ($vo2["xl_ckhb"]); else: ?>无<?php endif; ?>
									</p>
								</div>
							</div>
						</div><?php endforeach; endif; else: echo "" ;endif; ?>
				</div>

				<!--消费透明-->
				<h1 id="xftm" class="am-u-lg-12 title">消费透明</h1>
				<div class="am-u-lg-12 xcap_box">
					<div class="am-u-lg-6 xftm_box">
						<h3 class="title18">费用包含</h3>
						<p><?php echo ($line["xl_bh"]); ?></p>

					</div>
					<div class="am-u-lg-6 xftm_box">
						<h3 class="title18">费用不含</h3>
						<p><?php echo ($line["xl_bbh"]); ?></p>
					</div>
				</div>

				<!--预订须知-->
				<?php if($line['xl_ydts'] OR $line['xl_tkzc']): ?><h1 id="ydxz" class="am-u-lg-12 title">预订须知</h1>
				<div class="am-u-lg-12 xcap_box">
					<div class="am-u-lg-12 ydxz_box">
						<?php if($line['xl_ydts']): ?><h3 class="title18 am-text-center">旅游线路预订须知</h3>
						<p>
							<?php echo str_replace(PHP_EOL,'<br/>',$line['xl_ydts']);?>
						</p><?php endif; ?>
						<?php if($line['xl_tkzc']): ?><h3 class="title18 am-text-center">旅游线路退改政策</h3>
						<p>
							<?php echo str_replace(PHP_EOL,'<br/>',$line['xl_tkzc']);?>
						</p><?php endif; ?>
					</div>
				</div><?php endif; ?>

				<!--选择日期与人数-->
				<h1 id="yd" class="am-u-lg-12 title">选择日期及人数</h1>
				<div class="am-u-lg-12 xcap_box">
					<div class="am-u-lg-8 date_box">
						<input id="pickDateBtn" type="text" value="<?php echo date('Y-m-d');?>" style="display: none;">
					</div>
					<div class="am-u-lg-4 xz_rs">
						<div class="am-u-lg-12 pull_hight">
							<div class="am-u-lg-9 am-pagination-right line_price s_price">
								<?php echo ($line["xl_qijia"]); ?>
							</div>
							<div class="am-u-lg-3 line_dw">
								<p><?php echo ($line["xl_qijia_dw"]); ?>起</p>
							</div>
							<div class="am-u-lg-12 cfts">
								<span>出发地：<?php echo ($line["xl_cf_city"]); ?> </span><span>出游天数：<?php echo ($line["xl_cttsws"]); ?></span>
							</div>
							<div class="am-u-lg-12 xz_bg_box">
								<span>选择人数 </span>
							</div>
							<div class="am-u-lg-12">
								<span class="am-u-lg-6 am-padding-left-sm">成人人数</span>
								<span class="am-u-lg-6 am-padding-left-sm">儿童人数</span>
							</div>
							<div class="am-u-lg-12">
								<div class="am-u-lg-6 am-padding-left-sm">
									<div class="number-group clear">
										<input class="jiannum" type="button" value="-">
										<input class="numval" type="text" value="0" id="adultnum">
										<input class="jianum" type="button" value="+">
									</div>
								</div>
								<div class="am-u-lg-6 am-padding-left-sm">
									<div class="number-group clear">
										<input class="jiannum" type="button" value="-">
										<input class="numval" type="text" value="0" id="childnum">
										<input class="jianum" type="button" value="+">
									</div>
									
								</div>

							</div>
							<div class="am-u-lg-12 yd_gp">
								<div class="am-u-lg-7">
									<p class="" style='color:orange'>咨询电话</p>
									<p style='color:orange'>4000096828</p>
								</div>
								<div class="am-u-lg-5">
									<button class="am-btn am-btn-warning am-btn-xl am-radius" id="ydsubmit">立即预订</button>
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
										<h2 class="title16"><?php echo ($vo[6]); ?></h2>
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

			</div>
		</div>
		<div class="model">
			<div class="model_cnt">
				<span class="close_btn">X</span>
				<h1 class="title18 am-text-center">选择套餐</h1>
				<p class="rztime">2016年6月30日入住</p>
				<div class="hotel_type_box am-g">
					<?php if(is_array($lineinfo)): $k = 0; $__LIST__ = $lineinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><!--套餐row-->
						<div class="am-u-lg-12 hotel_type_row" data-start="<?php echo ($vo[2]); ?>" data-end="<?php echo ($vo[3]); ?>" style="display:none;">
							<div class="am-u-lg-2">
								<input type="radio" name="hotel-t" data-price="<?php echo ($vo[5]); ?>" data-pid='<?php echo ($vo[1]); ?>' data-setname="<?php echo ($vo[6]); ?>">
								<!-- <input type="hidden" class="lineinfo" name="startdate" data-price='' data-end='' data-week='' data-pid='<?php echo ($vo[1]); ?>' value="" data-setname="<?php echo ($vo[6]); ?>"/> -->
							</div>
							<div class="am-u-lg-6">
								<h2 class="title16" style="font-size:1.8rem;"><?php echo ($vo[2]); ?><br/><?php echo ($vo[3]); ?></h2>
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
		<script>
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
			$(function(){
			var pp = $(".pp");
				$.datetimepicker.setLocale('ch');
			Date.prototype.format = function(format) {
			       var date = {
			              "M+": this.getMonth() + 1,
			              "d+": this.getDate(),
			              "h+": this.getHours(),
			              "m+": this.getMinutes(),
			              "s+": this.getSeconds(),
			              "q+": Math.floor((this.getMonth() + 3) / 3),
			              "S+": this.getMilliseconds()
			       };
			       if (/(y+)/i.test(format)) {
			              format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
			       }
			       for (var k in date) {
			              if (new RegExp("(" + k + ")").test(format)) {
			                     format = format.replace(RegExp.$1, RegExp.$1.length == 1
			                            ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
			              }
			       }
			       return format;
			}
			var mindate = 0;
			var maxdate = 0;
			$.each(pp, function(){
				var tmpMin = new Date($(this).data("min"));
				var tmpMax = new Date($(this).data("max"));
				if(mindate == 0){
					mindate = tmpMin;
				}
				if(maxdate == 0){
					maxdate = tmpMax;
				}

				if(tmpMin < mindate){
					mindate = tmpMin;
				}
				if(tmpMax > maxdate){
					maxdate = tmpMax;
				}
			})

			var minDate = mindate.format('yyyy/MM/dd');
			var maxDate = maxdate.format('yyyy/MM/dd');
			$(".close_btn").on("click",function(){
				closemodel();
			});
			$("#pickDateBtn").datetimepicker({
				timepicker: false,
				format: 'Y-m-d',
				minDate: minDate,
				maxDate: maxDate,
				inline: true,
				scrollMonth:false,
				scrollTime:false,
				scrollInput:false,
				//				opened:true,
				onChangeDateTime: function() {},
				onSelectDate: function(){
					var dateinput1 = $('#pickDateBtn').val();
					var tempcount = 0;
					dateinput = Date.parse(dateinput1);
					if(pp.length > 1){
						var modelList = $(".hotel_type_row");
						$.each(modelList, function(){
							var max = Date.parse($(this).data("end"));
							var min = Date.parse($(this).data("start"));
							if(min <= dateinput && max >= dateinput){
								$(this).show();
								tempcount++;
							}
						})
						if(tempcount > 1){
							$(".rztime").html(dateinput1 + "出行");
							$(".model").show();
						}else{
							for(var i = 0; i < pp.length; i++){
								var max = Date.parse(pp.eq(i).data("max"));
								var min = Date.parse(pp.eq(i).data("min"));
								if(min <= dateinput && max >= dateinput){
									$('#priceid').val(pp.eq(i).data("pid"));
									$('.s_price').text(pp.eq(i).data("price"));
									closemodel();
								}else{
									return false;
								}
							}
						}
					}else{
						for(var i = 0; i < pp.length; i++){
							var max = Date.parse(pp.eq(i).data("max"));
							var min = Date.parse(pp.eq(i).data("min"));
							if(min <= dateinput && max >= dateinput){
								$('#priceid').val(pp.eq(i).data("pid"));
								$('.s_price').text(pp.eq(i).data("price"))
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
								var max = Date.parse($(this).data("max"));
								var min = Date.parse($(this).data("min"));
								if(min - 24 * 60 * 60 * 1000 <= tdDate && max >= tdDate){
									//$(_this).find("div").append("<br/><span style='color:red'>" + $(this).data("price") + "</span>");
									if($(_this).find("div").has("span").length > 0){
										$(_this).find("div span").html($(this).data("price"));
									}else{
										$(_this).find("div").append("<br/><span style='color:red'>" + $(this).data("price") + "</span>");
									}
								}
							})
						}
					})
				}
			});
			$('#table-myorder').DataTable({
				"paging": false,
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
			$('#table-myorder2').DataTable({
				"paging": false,
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
			//数字框组加减
			$(".jiannum").on("click", function() {
				var nmval = $(this).siblings(".numval").val();
				if (nmval == 0) {
				} else {
					$(this).siblings(".numval").val(--nmval);
				}
			});
			$(".jianum").on("click",function() {
				var nmval =  $(this).siblings(".numval").val();
				$(this).siblings(".numval").val(++nmval);
			});
			
			//行程安排锚点跳转
			$(".xc_to_btn a").on("mouseenter", function() {
				$(this).html('第' + ($(this).index() + 1) + '天');
			});
			$(".xc_to_btn a").on("mouseout", function() {
				$(this).html('D' + ($(this).index() + 1));
			});
			
			
			
			var dateinput1 = $('#pickDateBtn').val();
			dateinput = Date.parse(dateinput1);
			
			$.each(pp, function(){
				var max = Date.parse($(this).data("max"));
				var min = Date.parse($(this).data("min"));
				if(min <= dateinput && max >= dateinput){
					$('#priceid').val($(this).data("pid"));
					$('.s_price').text($(this).data("price"))
				}
			})

			$('#ydsubmit').on('click',function(){
				if(!$('#priceid').val()){
					alert('请选择规定的出行日期');
					return false;
				}
				if(!$('#pickDateBtn').val()){
					alert('请选择出行日期');
					return false;
				}
				if($('#adultnum').val() == 0){
					alert('请选择成人人数');
					return false;
				}
				if(!$('#childnum').val()){
					$('#childnum').val(0);
				}	
				var price = $('.s_price').eq(0).text();
				var num = parseInt($('#adultnum').val())+parseInt($('#childnum').val());
				location.href = "./index.php?s=/Home/Line/order/xlid/"+<?php echo ($lineid); ?>+"/cfdate/"+$('#pickDateBtn').val()+"/priceid/"+$('#priceid').val()+"/adult/"+$('#adultnum').val()+"/child/"+$('#childnum').val()+"/num/"+num+"/price/"+price+".html";
			});
			//收藏
			
			})

			function favor() { 
				var favorid="<?php echo I('xl_id');?>";
				var price=$('.line_price').eq(0).text();
					$.ajax({
				type:'post', //传送的方式,get/post
				url:'<?php echo U("Collect/add");?>', //发送数据的地址
				data:{id:favorid,price:price,goodstype:3,goodsimg:$('#goodsimg').val(),goodsname:$('#goodsname').val()},
				 dataType: "json",
				success:function(data){
						if(data.status == 0){
							alert(data.info);
							return false;
						}
						alert(data.msg)
					}
				})
			}


			$(function(){
				var imgp = $(".xlts_box p").has("img");
				$.each(imgp, function(i){
					$(this).css({
						"text-align": "center"
					})
					$(this).find("img").css({
						"float": "none"
					});

				})


				var tobtnHigh;
				var tobtnBottom;
				var linenav;
				$(document).ready(function() {
					tobtnHigh = $("#xcap").offset().top;
					if($("#ydxz").length > 0){
						tobtnBottom = $("#ydxz").offset().top;
					}
					linenav = $('#linenav').offset().top;
				});
				// console.log(tobtnBottom);
				window.onscroll = function(e) {
					var e = e || window.event;
					var scrolltop = document.documentElement.scrollTop || document.body.scrollTop;
					var scrolltop1 = document.documentElement.scrollTop || document.body.scrollTop;
					if (scrolltop >= tobtnHigh && scrolltop <= tobtnBottom) {
						$(".xc_to_btn").css("position", "fixed").css("display", "block").css("top", "200px").css("left", "50%").css("margin-left", "-610px");
					} else {
						$(".xc_to_btn").css("position", "absolute").css("display", "none").css("left", "").css("margin-left", "-89px");
					}
					if (scrolltop1 >= linenav) {
						$("#linenav").addClass("navfixed");
					} else {
						$("#linenav").removeClass("navfixed");
					}
				}
			})

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