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
	
	<title>酒店预订</title>
	<link type="text/css" href="/Public/Home/css/selectpick.css" rel="stylesheet"/>
	<link type="text/css" href="/Public/Home/css/city.css" rel="stylesheet"/>
	<!--<link rel="stylesheet" href="/Public/Home/css/amazeui.min.css">-->
	<link rel="stylesheet" href="/Public/Home/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="/Public/Home/css/style.css">
	<!--[if lt IE 9]>
		<link rel="stylesheet" href="/Public/Home/css/css/style1-IE.css" />
		<script type="text/javascript" src="/Public/Home/js/jquery-1.11.0.js" ></script>
		<script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  		<script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
		<script type="text/javascript" src="/Public/Home/js/amazeui.ie8polyfill.min.js" ></script>
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

		<style>
			.am-u-sm-2{
				float: left;
				width: 20%;
				height: 50px;
				display: block;
				margin: 0;
				margin-top: 10px;
			}
			.am-u-sm-10{
				float: left;
				width: 70%;
				height: 50px;
				display: block;
				margin: 0;
				margin-top: 10px;
			}
		</style>
		<!--section-->
		<div class="my_main">
			<div class="am-g am-g-fixed am-g-collapse">
				<!--酒店信息-->
				<div class="am-u-lg-12 ad_box_group">
					<h1>酒店信息</h1>
					<div class="jd_list">
						<div id="table-xl_wrapper" class="dataTables_wrapper no-footer"><table id="table-xl" class="row-border table-myorder dataTable no-footer" role="grid">
							<thead>
								<tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" style="width: 423px;">酒店名称</th><th class="sorting_disabled" rowspan="1" colspan="1" style="width: 198px;">酒店所在地</th><th class="sorting_disabled" rowspan="1" colspan="1" style="width: 101px;">数量</th><th class="sorting_disabled" rowspan="1" colspan="1" style="width: 172px;">入住时间</th></tr>
							</thead>
							<tbody>
								
								
							<tr role="row" class="odd">
									<td><?php echo ($cache["xl_name"]); ?></td>
									<td><?php echo ($cache["xl_jd_address"]); ?></td>
									<td><?php echo I('num');?></td>
									<td><?php echo I('cfrq');?></td>
								</tr></tbody>
						</table></div>
					</div>
					
				</div>
				<!--填写并核对订单信息-->
				<div class="am-u-lg-12 ad_box_group">
					<div class="place">
					<?php if(empty($address)): ?><div id="senderdetail"></div>
						<div id="formsender" style="position:relative;">
							<form id="formincart" name="form" class="am-form am-form-inline">
								<!--<div class="">
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
								</div>-->
								<!--<div class="am-u-sm-2 am-margin-top-sm">
									<label>详细地址：</label>
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<input type="text" class="cart_long" id="address" maxlength="40" data-input="text" value="" name="area" placeholder="详细地址不能为空">
								</div>-->
								<div class="am-u-sm-2 am-margin-top-sm">
									<label>入住人：</label>
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<input type="text" class="cart_long"  style="width: 200px;" id="realname" maxlength="20" data-input="text" value=""><font class="ml10 cleb6100" style="display: none;">入住人不能为空</font>
								</div>
								<div class="am-u-sm-2 am-margin-top-sm">
									<label>手机号：</label>
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<input type="text" class="cart_long" style="width: 200px;" id="phone" maxlength="11" data-msg="入住人手机号码格式不正确" data-input="text" data-type="cellphone" value="">&nbsp;
								</div>
								<div class="am-u-sm-2 am-margin-top-sm">
									<span class="am-margin-left"><input id="isdefault" checked="checked" name="default" type="checkbox" class="cart_n_box"> 设为默认</span>
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<a href="javascript:void(0)" class="ncart_btn_on am-btn am-btn-primary" onclick="savemsg();">保存</a>
								</div>
								<div class="am-cf"></div>
							</form>
						</div>
						<?php else: ?>
						<div id="hasaddress">
							<div id="senderdetail">
								<p id="f_default">
									<?php if(is_array($address)): $i = 0; $__LIST__ = $address;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$a): $mod = ($i % 2 );++$i;?><input type="radio" <?php if(($a["id"]) == $default_id): ?>id="default" checked='checked'<?php endif; ?> name="sender" value="<?php echo ($a["id"]); ?>"/>&nbsp;入住人：<?php echo ($a["realname"]); ?>&nbsp;&nbsp;联系电话:<?php echo ($a["cellphone"]); ?><br/><?php endforeach; endif; else: echo "" ;endif; ?>
								</p>
							</div>
							<a href="javascript:void(0);" id="addaddress">新增入住人</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="javascript:void(0);" id="setaddress">修改</a>
						</div>
						<div id="formsender" style="position:relative;display: none;">
							<form id="formincart" name="form" class="am-form am-form-inline">
								<div class="am-u-sm-2 am-margin-top-sm">
									<label>入住人：</label>
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<input type="text" class="cart_long"  style="width: 200px;" id="realname" maxlength="20" data-input="text" value=""><font class="ml10 cleb6100" style="display: none;">入住人不能为空</font>
								</div>
								<div class="am-u-sm-2 am-margin-top-sm">
									<label>手机号：</label>
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<input type="text" class="cart_long" style="width: 200px;" id="phone" maxlength="11" data-msg="入住人手机号码格式不正确" data-input="text" data-type="cellphone" value="">&nbsp;
								</div>
								<div class="am-u-sm-2 am-margin-top-sm">
									<span class="am-margin-left"><input id="isdefault" checked="checked" name="default" type="checkbox" class="cart_n_box"> 设为默认</span>
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<a href="javascript:void(0)" class="ncart_btn_on am-btn am-btn-primary" onclick="savemsg1();">保存</a>
									<a href="javascript:void(0)" class="ncart_btn_on am-btn am-btn-primary" onclick="quxiao1();">取消</a>
								</div>
								<div class="am-cf"></div>
							</form>
						</div>
						<div id="formsender1" style="position:relative;display: none;">
							<form id="formincart1" name="form" class="am-form am-form-inline">
								<input type="hidden" value="" id='addid'>
								<div class="am-u-sm-2 am-margin-top-sm">
									<label>入住人：</label>
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<input type="text" class="cart_long"  style="width: 200px;" id="realname1" maxlength="20" data-input="text" value=""><font class="ml10 cleb6100" style="display: none;">入住人不能为空</font>
								</div>
								<div class="am-u-sm-2 am-margin-top-sm">
									<label>手机号：</label>
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<input type="text" class="cart_long" style="width: 200px;" id="phone1" maxlength="11" data-msg="入住人手机号码格式不正确" data-input="text" data-type="cellphone" value="">&nbsp;
								</div>
								<div class="am-u-sm-2 am-margin-top-sm">
									<!--<span class="am-margin-left"><input id="isdefault1" checked="checked" name="default" type="checkbox" class="cart_n_box"> 设为默认地址</span>-->
								</div>
								<div class="am-u-sm-10 am-margin-bottom-xs">
									<a href="javascript:void(0)" class="ncart_btn_on am-btn am-btn-primary" onclick="save();">保存</a>
									<a href="javascript:void(0)" class="ncart_btn_on am-btn am-btn-primary" onclick="quxiao();">取消</a>
								</div>
								<div class="am-cf"></div>
							</form>
						</div><?php endif; ?>
					
				</div>
				</div>
				
				
				
				<!--支付方式-->
				<div class="am-u-lg-12 zf_box_group">
					<h1>支付</h1>
					<h2 class="title18 pd_all_15">支付方式：</h2>
					<div class="zhfs">
						<input type="radio" checked="checked"><label>在线支付</label>
					</div>
				</div>
				<!--支付方式-->
				<div class="am-u-lg-12 zf_box_group">
					<h1>订单个性需求</h1>
					<div class="zhfs">
						<textarea style="width: 95%;margin: 2%;" rows="4" id="message"></textarea>
					</div>
				</div>
				<!--付款信息-->
				<div class="am-u-lg-12 am-text-right">
					
					<p>应付总额：<span class="sumpr blod am-text-warning s_price"><?php echo ($cache["yfze"]); ?></span>元</p>
					<button class="am-btn am-btn-lg am-btn-primary" id="ordersubmit">确认订单</button>
				</div>
				
			</div>
		</div>
		<script type="text/javascript" src="/Public/Home/js/jquery-2.1.0.js"></script>
		<script type="text/javascript" src="/Public/Home/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="/Public/Home/js/city.js"></script>
		<script>
			$('#table-xl').DataTable({
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
		</script>
		<script>
			//取消
			function quxiao() {
				$('#hasaddress').show();
				$('#formsender1').hide();
				$('#realname1').val('');
				$('#phone1').val('');
			}
			//取消2
			function quxiao1() {
				$('#hasaddress').show();
				$('#formsender').hide();
			}
			//保存
			function save() {							
			var id=$("#addid").val();
			var realname = $("#realname1").val();
			var cellphone = $("#phone1").val();
			if(!realname){
				alert('入住人不能为空！')
				return false;
			}
			if(!cellphone){
				alert('手机号不能为空！')
				return false;
			}
//			alert(id);
			$.ajax({
				type: 'post', //传送的方式,get/post
				url:"<?php echo U('Home/Hotel/addsave');?>",
				data: {
					id:id,
					realname: realname,				
					cellphone: cellphone
				},
				dataType: "json",
				success: function (e) {
					if(e.status==1){
						alert(e.msg);
						location.reload();
					}else{
						alert(e.msg);
					}
				},
				error: function (event, XMLHttpRequest, ajaxOptions, thrownError) {
					alert("表单错误，" + XMLHttpRequest + thrownError);
				}
			})
				
				
			}
			
			
		
			//新增
			$('#addaddress').on('click',function(){
				$('#hasaddress').hide();
				$('#formsender').show();
				$('#realname').val('');
				$('#phone').val('');
			});
			//修改
			$('#setaddress').on('click',function(){
				var val = $('input:radio[name="sender"]:checked').val();
				//判断新地址是否选中,获取地址id
				if (val == null) {
					alert("请选择一个地址!");
					return false;
				}
				$('#hasaddress').hide();
				$('#formsender1').show();
//				alert(val);
				$.ajax({
					type:"post",
					url:"<?php echo U('Home/Hotel/addset');?>",
					async:true,
					data:{
						'addid':val
					},
					success:function(e){
						if(e.status==1){
							var add=e.add;
							$('#realname1').val(add.realname);
							$('#phone1').val(add.cellphone);
							$('#addid').val(add.id);
						}else{
							alert(e.msg);
						}
					}
				});
								
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
				url: '<?php echo U("Address/build1");?>', //发送数据的地址
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
					location.reload();
				},
				error: function (event, XMLHttpRequest, ajaxOptions, thrownError) {
					alert("表单错误，" + XMLHttpRequest + thrownError);
				}
			})
		}
			
			function savemsg1() {
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
			var username = $.trim($("#realname").val());
			var phone = $.trim($("#phone").val());
			var b = $("#youbian").val();


			if(username == ""){
				alert("请输入姓名。");
				return false;
			}

			if(phone == ""){
				alert("请输入手机号。");
				return false;
			}

			if(phone.length != 11){
				alert("请输入正确的手机号。");
				return false;
			}

			$.ajax({
				type: 'post', //传送的方式,get/post
				url: '<?php echo U("Address/build1");?>', //发送数据的地址
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
					location.reload();
				},
				error: function (event, XMLHttpRequest, ajaxOptions, thrownError) {
					alert("表单错误，" + XMLHttpRequest + thrownError);
				}
			})
		}
			
			$('#ordersubmit').on('click',function(){
				var val = $('input:radio[name="sender"]:checked').val();
				//判断新地址是否选中,获取地址id
				if (val == null) {
					alert("请选择一个地址!");
					return false;
				}
				var cfrq = "<?php echo I('cfrq');?>";
				var message = $('#message').val();
				var price = $('.s_price').text();
				var urlo = "./index.php?s=/Home/Hotel/orderdetail/num/<?php echo I('num');?>/qprice/<?php echo I('price');?>/addressid/"+val+"/rzsj/"+cfrq+"/jdid/"+<?php echo I('id');?>+"/priceid/<?php echo I('priceid');?>/price/"+price+"&message=" + message;
				location.href = urlo;
//				var data={
//					num:"<?php echo I('num');?>",
//					addressid:val,
//					message:$('#message').val(),
//					rzsj:"<?php echo I('cfrq');?>",
//					xl_id:"<?php echo I('id');?>"
//				};
//				$.ajax({
//					type:"post",
//					url:"<?php echo u('Home/Hotel/ordermake');?>",
//					async:true,
//					data:data,
//					success:function(e){
//						if(e.status==1){
//							var url='<?php echo u("Home/Pay/index");?>'+"/orderid/"+e.orderid;
//							window.location.href='<?php echo u("Home/Pay/index");?>'+"&orderid="+e.orderid;
//						}else{
//							alert(e.msg);
//						}
//					}
//				});
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