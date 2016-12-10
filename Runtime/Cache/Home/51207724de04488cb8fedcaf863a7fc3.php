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
	
    <link rel="stylesheet" href="/Public/Home/css/order.css">
    <style>
        a.trash{
            background-color:transparent;
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

    <div class="am-g am-container am-margin-top-lg">
        <div class="breadcrumb-box">
            <ol class="am-breadcrumb am-breadcrumb-slash">
                <li><a href="<?php echo U('Home/Index/index');?>">首页</a></li>
                <li class="am-active">个人中心</li>
            </ol>
        </div>
        <div class="userCenter am-cf">
            <div class="am-u-sm-3">
                <!-- 左侧菜单 -->
                <ul class="am-nav am-padding-xl">
	<li class="am-nav-header"><h3><a class="home-me">订单中心</a></h3>
		<ul class="am-nav">

			<li><a href='<?php echo U("Center/allorder");?>'>普通订单</a></li>
			<li><a href='<?php echo U("Jipiao/orderList");?>'>机票订单</a></li>
			<li><a href='<?php echo U("Hotel/allorder");?>'>酒店订单</a></li>
			<li><a href='<?php echo U("Line/allorder");?>'>旅游订单</a></li>
			<li><a href='<?php echo U("SuiteOrder/allorder");?>'>体检预约</a></li>
			<li><a href='<?php echo U("UserCoupon/index");?>'>优 惠 券</a></li>
			<!--<li><a href='<?php echo U("Comment/lists");?>'>评价管理</a></li>-->
		</ul>
	</li>
	<li class="am-nav-header"><h3><a class="home-me">个人中心</a></h3>
		<ul class="am-nav">
			<li><a href='<?php echo U("Center/index");?>'>我的主页</a></li>
			<li><a href='<?php echo U("Member/index");?>'>个人资料</a></li>
			<li><a href='<?php echo U("Report/index");?>'>报告历史</a></li>
			<li><a href='<?php echo U("Address/index");?>'>收货地址</a></li>
			<li><a href='<?php echo U("User/profile");?>'>修改密码</a></li>
			<!--<li><a href='<?php echo U("Account/security");?>'>安全中心</a></li>-->
			<!--<li><a href='<?php echo U("Shopcart/index");?>'>我的购物车</a></li>-->
			<!--<li><a href='<?php echo U("UserEnvelope/index");?>'>站内信</a></li>-->
			<li><a href='<?php echo U("Collect/index");?>'>我的收藏</a></li>
		</ul>
	</li>
</ul>

<script>/* 左边菜单高亮 */
url = window.location.pathname + window.location.search;
url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
$("a[href='" + url + "']").addClass("am-active");
</script>
   
                <!-- 左侧菜单 -->
            </div>
            <div class="center_right" style="display: none;">
                <div class="center_right_loading"></div>
            </div>
            <div id="memberCenter" class="userCenter-r am-u-sm-9 am-padding-xl">
                <!-- 个人中心 初始状态 start -->
                <div class="am-g" id="MemberCenterCtrl">
                    <!--标题-->
                    <div class="am-cf">
                        <div class="am-u-sm-6"><h2 class="am-fl">订单管理</h2></div>
                    </div>
                    <!--标题-->
                    <!--<div class="am-tabs am-margin-left">
                        <ul class="am-tabs-nav am-nav am-nav-tabs">
                            <li class="am-active"><a href='<?php echo U("Center/allorder");?>' class="red">所有订单</a></li>
                            <li><a href='<?php echo U("Center/needpay");?>' class="red">待支付订单</a></li>
                            <li><a href='<?php echo U("Center/tobeshipped");?>' class="red">待发货订单</a></li>
                            <li><a href='<?php echo U("Center/tobeconfirmed");?>' class="red">待确认订单</a></li>
                        </ul>
                    </div>-->
                    <?php if(empty($list)): ?><p class="am-text-center am-padding-xl">
                            没有订单，<a href='<?php echo U("Jipiao/index");?>' class="am-text-warning">快去下单吧</a></p>
                        <?php else: ?>
                        <form action='<?php echo U("order/del");?>' method="post" name="delform">
                            <div class="order_del am-padding-top am-padding-bottom am-hide">
                                <!--<span>-->
                                <!--<input class="checkbox check-all" type="checkbox"> 全选 <a href='javascript:void(0)' onclick="delorder()">删除选中的订单</a> </span>-->
                            </div>
                            <div class="good-canshu am-margin-top">
                                <span class="good-name am-text-left am-padding-left">机票信息</span>
                                <span class="good-price">&nbsp;</span><span class="good-num">&nbsp;</span>
                                <span class="good-action">&nbsp;</span><span class="good-total">总金额(元)</span>
                                <span class="good-status">交易状态</span> <span class="deal-action">交易操作</span>
                            </div>
                            <?php $i = 0; ?>
                            <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$po): $mod = ($k % 2 );++$k;?><div class="singlehover" onmouseover="this.className='singlehover'" onmouseout="this.className='single'">
                                    <?php if($k == 1 || ($list[$k - 1]["realid"] != $list[$k - 2]["realid"])): ?>
                                    <div class="order-detail am-text-sm">
                                        机票订单号：<span class="am-link-muted num-text"><?php echo ($po["realid"]); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    <!-- <div class="order-detail am-text-sm">
                                        分订单号：<span class="am-link-muted num-text"><?php echo ($po["orderid"]); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;下单时间：<span class="am-link-muted num-text"><?php echo (date('Y-m-d H:i:s',$po["addtime"])); ?>&nbsp;</span>
                                    </div> -->
                                    <!-- 列表详情区域开始 -->
                                    <div id="table4" style="padding-bottom:10px;">
                                        <div id="table3">
                                            <div id="table2">
                                                <div class="goodlist-wrap am-cf">
                                                    <!-- 左边商品区域开始 -->
                                                    <div class="three-area">
                                                        <div style="width: 100%;margin-top: .5em;text-indent: 1em;">
                                                            <span style="display: inline-block;text-align: center;">
                                                                <?php echo ($po["cabinrankdetail"]); ?><br />
                                                                (<?php echo ($po["departuredate"]); ?>)
                                                            </span>
                                                            <span style="display: inline-block;text-align: center;">
                                                                <div><?php echo ($po['fromcitycn']); ?></div>
                                                                <div><?php echo ($po["departuretime"]); ?></div>
                                                                <div style="font-size:10px;">
                                                                <?php echo setAirport($po["fromairport"]);?>
                                                                </div>
                                                            </span>
                                                            <img src="/Public/hangtian/img/tc_sc.png" style="width:110px;line-height: 3em;margin-top:-50px;">
                                                            <span style="display: inline-block;text-align: center;">
                                                                <div><?php echo ($po['tocitycn']); ?></div>
                                                                <div><?php echo ($po["arrivaltime"]); ?></div>
                                                                <div style="font-size:10px;">
                                                                <?php echo setAirport($po["toairport"]);?>
                                                                </div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <!-- 左边商品结束 -->
                                                    <?php $ck = $po["passengername"]; $ck = explode("|", $ck); $ck = count($ck); ?>
                                                    <div class="total-area">
                                                        <p class="am-text-warning num-text">￥<?php echo ($po['totalorderprice'] + 20 * $ck); ?></p>
                                                        <p class="am-text-xs" style="line-height:16px;height:auto;">包含保险费</p>
                                                        <p class="am-text-xs" style="line-height:16px;height:auto;">
                                                        <?php if($po['bx']): ?>保单状态:<br/>
                                                                <?php switch($po['bx']['status']): case "1": ?>未支付<?php break;?>
                                                                    <?php case "2": ?>已支付<?php break;?>
                                                                    <?php case "3": ?>申请成功<?php break;?>
                                                                    <?php case "4": ?>已投保<?php break;?>
                                                                    <?php case "5": ?>已退保<?php break;?>
                                                                    <?php case "0": ?>已取消<?php break; endswitch; endif; ?>
                                                        </p>
                                                    </div>
                                                    <div class="deal-area">
                                                        <p class="red am-text-xs am-margin-0 ">
                                                            <?php switch($po['orderstatus']){ case 'WAIT_PAY': echo '待支付'; break; case 'PAYING': echo '支付中'; break; case 'WAIT_AUDIT': echo '待审核'; break; case 'AUDIT_FALSE': echo '审核失败'; break; case 'CANCELED': echo '已取消'; break; case 'WAIT_ISSUE': echo '待出票'; break; case 'PAY_FALSE': echo '支付失败'; break; case 'ISSUE_FALSE': echo '出票失败'; break; case 'ISSUED': echo '已出票'; break; case 'ISSUED_SUSPEND': echo '已出票（挂起）'; break; case 'ENDORSE_WAIT_AUDIT': echo '改签待审核'; break; case 'ENDORSE_AUDIT_SUCCESS': echo '改签审核成功'; break; case 'ENDORSE_AUDIT_FALSE': echo '改签审核失败'; break; case 'WAIT_REFUND': echo '待退票'; break; case 'REFUND_FALSE': echo '退票失败'; break; case 'CANCEL_REFUND': echo '取消退票'; break; case 'REFUND_FINISH': echo '退票成功'; break; case 'REFUND_WAIT_AUDIT': echo '退票待审核'; break; case 'REFUND_AUDIT_FALSE': echo '退票审核失败'; break; case 'CANCEL_ENDORSE': echo '取消改签'; break; case 'WAIT_ENDORSE': echo '待改签'; break; case 'ENDORSE_FALSE': echo '改签失败'; break; case 'ENDORSE_FINISH': echo '改签成功'; break; case 'ENDORSE_REVIEW_PASS': echo '改签后付款'; break; } ?>                                          </p>
                                                        <p class="am-text-xs am-margin-0 ">
                                                            <a href="<?php echo U('Jipiao/orderShow?oid='.$po['orderid']);?>" class="am-text-xs">订单详情</a>
                                                        </p>
                                                    </div>
                                                    <div class="act-area">
                                                        <?php if(($po['orderstatus']) == "WAIT_PAY"): ?><p>
                                                                <a href="<?php echo U('Jipiao/clear?oid='.$po['orderid']);?>">取消订单</a>
                                                                <a class="am-btn am-btn-warning am-btn-xs" href="<?php echo U('Pay/index', array('orderid'=>$po['orderid']));?>">前去支付</a>
                                                            </p>
                                                            <?php else: endif; ?>
                                                        <?php if($po['orderstatus'] == 'ISSUED' or $po['orderstatus'] == 'ISSUED_SUSPEND' or $po['orderstatus'] == 'ENDORSE_FINISH'): ?><p>
                                                                <a href="javascript:;" class="applyrefund" data-id="<?php echo ($po['orderid']); ?>" data-rule='<?php echo ($po['rule']); ?>'>申请退票</a>
                                                            </p><?php endif; ?>
                                                        <?php if(($po['orderstatus']) == "ISSUED"): ?><a href="<?php echo u('endorse',array('orderid'=>$po['orderid'],'sdate'=>$po['departuredate'],'edate'=>$po['departuredate1']));?>" data-id="<?php echo ($po['orderid']); ?>" data-chufa="<?php echo ($po['fromcitycn']); ?>" data-end="<?php echo ($po['tocitycn']); ?>">改签</a><?php endif; ?>

                                                        <?php if(($po['orderstatus']) == "ENDORSE_REVIEW_PASS"): ?><p class="am-text-xs" style="height:auto;line-height:16px;">差价：￥<?php echo ($po["payamount"]); ?></p>
                                                            <p>
                                                                <a class="am-btn am-btn-warning am-btn-xs" href="<?php echo U('Pay/index', array('orderid'=>$po['orderid']));?>">支付差价</a>
                                                            </p><?php endif; ?>
                                                    </div>
                                                    <!--<A href='index.php?s=/Home/cancel/index/id/".$po['orderid']."'>取消订单</a>-->
                                                </div>
                                                <!-- 列表详情区域结束 -->
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($po['iswf'] == 1): ?><div> 返程票:</div>
                                    <div id="table4" style="padding-bottom:10px;">
                                        <div id="table3">
                                            <div id="table2">
                                                <div class="goodlist-wrap am-cf">
                                                    <!-- 左边商品区域开始 -->
                                                    <div class="three-area">
                                                        <div style="width: 100%;margin-top: .5em;text-indent: 1em;">
                                                            <span style="display: inline-block;text-align: center;">
                                                                <?php echo ($po["cabinrankdetail1"]); ?><br />
                                                                (<?php echo ($po["departuredate1"]); ?>)
                                                            </span>
                                                            <span style="display: inline-block;text-align: center;">
                                                                <div><?php echo ($po['tocitycn']); ?></div>
                                                                <div><?php echo ($po["departuretime1"]); ?></div>
                                                                <div style="font-size:10px;">
                                                                <?php echo setAirport($po["fromairport1"]);?>
                                                                </div>
                                                            </span>
                                                            <img src="/Public/hangtian/img/tc_sc.png" style="width:110px;line-height: 3em;margin-top:-50px;">
                                                            <span style="display: inline-block;text-align: center;">
                                                                <div><?php echo ($po['fromcitycn']); ?></div>
                                                                <div><?php echo ($po["arrivaltime1"]); ?></div>
                                                                <div style="font-size:10px;">
                                                                <?php echo setAirport($po["toairport1"]);?>
                                                                </div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <!-- 左边商品结束 -->
                                                    <div class="total-area">
                                                        <p class="am-text-warning num-text"></p>
                                                        <p class="am-text-xs"></p>
                                                    </div>
                                                    <div class="deal-area">
                                                        <p class="red am-text-xs am-margin-0 "></p>
                                                        <p class="am-text-xs am-margin-0 ">
                                                        </p>
                                                    </div>
                                                    <!--<A href='index.php?s=/Home/cancel/index/id/".$po['orderid']."'>取消订单</a>-->
                                                </div>
                                                <!-- 列表详情区域结束 -->
                                            </div>
                                        </div>
                                    </div><?php endif; ?>
                                </div><?php endforeach; endif; else: echo "" ;endif; ?>
                        </form><?php endif; ?>

                    <!-- 分页 -->
                    <div class="page">
                        <?php echo ($page); ?>
                    </div>
                    <!--退票申请-->
                    <div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
                      <div class="am-modal-dialog">
                        <div class="am-modal-hd">确认是否申请退票?</div>
                        <div class="am-modal-bd">
                            <strong id="tuirule" style="display:block;color:red;font-size:14px;text-align:left;"></strong>
                            <select id="applytype">
                                <!--<option value="refundAtSaleDate">当日作废</option>-->
                                <option value="refundOnPassengerDemand">按客票规定自愿退票</option>
                                <option value="refundBecauseOfAirPlainProblem">民航原因（取消/延误）</option>
                                <option value="changeTicket">客票换开</option>
                                <option value="fullRefundBecauseOfDisease">因病全退</option>
                                <option value="onlyRefundTaxPayment">只退税款</option>
                                <option value="other">其它原因</option>
                            </select>
                        </div>
                        <div class="am-modal-footer">
                          <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                          <span class="am-modal-btn" data-am-modal-confirm>确定</span>
                        </div>
                      </div>
                    </div>
                    <script type="text/javascript">

                        /**
                         * 改签
                         */
//                      $('.endorse').datepicker().on('changeDate.datepicker.amui', function(event) {
//                          var url="/index.php?s=/Home/Jipiao/endorse/oid/" + $(this).data('id') + "/chufa/"+$(this).data('chufa')+"/end/"+$(this).data('end')+"/startdate/"+$(this).data('date');
//                          console.log(url);
//                          location.href=url;
//                      });
                        /**
                         * 退票申请
                         */
                        $('.applyrefund').on('click',function(){
                            var oid=$(this).data('id');
                            var rule = eval($(this).data("rule"));
                            console.debug(rule);
                            if(rule != null){
                                $("#tuirule").html("退票规则：" + (rule.ticketBounceRemark || ""));
                            }
                            $('#my-confirm').modal({
                                relatedTarget: this,
                                onConfirm: function(options) {
                                    var applytype=$('#applytype').val();
                                    $.ajax({
                                        type:"post",
                                        data:{
                                            oid:oid,
                                            applytype:applytype
                                        },
                                        url:"<?php echo U('Jipiao/refund');?>",
                                        async:true,
                                        success:function(e){
                                            topAlert(e.info, e.status);
                                            if(e.status==1){
                                                setTimeout(function () {
                                                    location.href = e.url;
                                                }, 2000);
                                            }
                                        }
                                    });
                                }
                             });
                        });
                        
                        function delorder() {
                            if (confirm("确认吗?")) {
                                document.delform.submit();
                            }

                        }
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


                    </script>
                    <!-- 个人中心 初始状态 end --></div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // _init_area();
    </script>
    <script type="text/javascript">
        $(".trash").click(function(){
            if(confirm("是否确认?")){
                return true;
            }else{
                return false;
            }
        });
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