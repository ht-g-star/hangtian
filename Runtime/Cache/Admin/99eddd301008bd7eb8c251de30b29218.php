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
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">会员管理</strong> /
			<small>用户列表</small>
		</div>
	</div>


		
	<div class="am-g">
		<div class="am-u-sm-12 ">
			<div class="am-btn-toolbar">
				<div class="am-u-sm-10 am-btn-group am-fl" style="margin-left:-10px;">
					<button type="button" id="btn_add" class="am-btn am-btn-default am-text-primary am-btn-xs" data-am-modal="{target: '#pop_add'}">
						<span class="am-icon-plus"></span> 新增
					</button>
					<button type="button" id="btn_edit" class="am-btn am-btn-default am-btn-xs am-text-warning am-disabled need_select">
						<span class="am-icon-pencil-square-o"></span> 编辑
					</button>
					<button type="button" id="btn_del" class="am-btn am-btn-default confirm am-btn-xs am-text-danger am-disabled need_select">
						<span class="am-icon-trash-o"></span> 删除
					</button>
					<button type="button" id="btn_froze_money" class="am-btn am-btn-xs am-btn-default">
						<span class="am-icon-lock"></span> 押金
					</button>
					<button type="button" id="btn_charge" class="am-btn am-btn-xs am-btn-default">
						<span class="am-icon-money"></span> 充值
					</button>
					<button type="button" id="btn_consume" class="am-btn am-btn-xs am-btn-default">
						<span class="am-icon-yen"></span> 消费
					</button>

					<button type="button" id="btn_open_card" class="am-btn am-btn-xs am-btn-default  am-disabled need_select">
						<span class="am-icon-chain"></span> 开卡
					</button>
					<button type="button" id="btn_recard" class="am-btn am-btn-xs am-btn-default am-disabled need_select">
						<span class="am-icon-copy"></span> 补卡
					</button>
					<button type="button" id="btn_lost" class="am-btn am-btn-xs am-btn-default">
						<span class="am-icon-tag"></span>
						挂失
					</button>

					<button type="button" id="btn_repass" class=" am-btn-xs am-btn am-btn-default">
						<span class="am-icon-lock"></span>
						重置密码
					</button>

					<button type="button" id="btn_view" class=" am-btn-xs am-btn am-btn-default am-disabled need_select">
						<span class="am-icon-eye"></span>
						查看
					</button>
					<button type="button" id="btn_balance" class=" am-btn-xs am-btn am-btn-default am-disabled need_select">
						<span class="am-icon-yen"></span>
						金额调整
					</button>
				</div>
				<div class="am-btn-group am-btn-group-xs" style="position: absolute;right: 0;margin-right: 20px; height:75px;">					
					<button type="button" class="am-btn am-btn-default" data-am-modal="{target: '#pop_import'}">
						<span class="am-icon-plus"></span> 统筹团体导入
					</button>
					<button class="am-btn am-btn-default" id="export" data-href="<?php echo U('export');?>" ><span class="am-icon-file"></span> 导出
					</button>
					<button type="button" class="am-btn am-btn-default" style="clear:both;" data-am-modal="{target: '#pop_import_tuan'}">
						<span class="am-icon-plus"></span> 外部团体导入
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="am-g" style="width:80%;margin-left:0;">
		<div class="am-u-sm-12">
			<div class="am-btn-group am-btn-group-xs">
				<form class="am-form" method="get" action="/Admin.php/Member/tuanjian.html">
					<label>选择单位筛选</label>
					<select name="company" id="company">
						<option value="0">请选择.</option>
						<?php if(is_array($companys)): $i = 0; $__LIST__ = $companys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i; if(!empty($c["company"])): ?><option value="<?php echo ($c["company"]); ?>" <?php if(($_POST['company']) == $c['company']): ?>selected<?php endif; ?>><?php echo ($c["company"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
					</select>
					<button type="button" id="query" class="am-btn am-btn-primary am-btn-xs" >确定</button>
					<!-- button type="button" class="am-btn am-btn-warning am-btn-xs" >安排单位体检</button> -->
					<input type="submit" class="am-btn am-btn-success" value="安排单位体检">

				</form>
			</div>
		</div>
	</div>

	<div class="am-g am-datatable-hd">
		<div class="am-u-sm-12 ">
			<form class="am-form">
				<div id="scrollable-table" class="am-scrollable-horizontal"> <!---->
					<table class="am-table am-table-striped am-table-hover am-table-compact am-text-nowrap" id="data-list" style="width:100%;">
						<thead>
						<tr>
							<th>ID</th>
							<th>会员卡号</th>
							<th>单位</th>
							<th>部门</th>
							<th>职位</th>
							<th>是否一线</th>
							<th>姓名</th>
							<th>性别</th>
							<th>身份证</th>
							<th>手机</th>
							<th>金额</th>
							<th>等级</th>
							<th>类型</th>
							<th>婚否</th>
							<th>状态</th>
						</tr>
						</thead>
					</table>

				</div>
			</form>

		</div>

	</div>


	<!--popup 导入-->
<div class="am-popup" id="pop_import">
	<form action="<?php echo U('import');?>" method="post" enctype="multipart/form-data">
		<div class="am-popup-inner">
			<div class="am-popup-hd">
				<h4 class="am-popup-title">统筹团体批量导入</h4>
				<span data-am-modal-close class="am-close">&times;</span>
			</div>
			<div class="am-popup-bd">
				<!--begin-->
				<form class="am-form form-horizontal am-form-horizontal" method="post" action="" enctype="multipart/form-data">
					<div class="am-u-sm-12 am-margin-top-lg">
						<div class="am-u-sm-9">
							<div class="am-form-file am-text-left">
								<button type="button" class="am-btn am-btn-default am-btn-block am-margin-left-0">
									<span class="am-icon-cloud-upload"></span> 请选择要上传的文件
								</button>
								<input name="members" id="imports" type="file" >
							</div>
							<div id="file-list"></div>
						</div>
						<div class="am-u-sm-3">
							<div class="am-form-group am-text-left am-margin-left-0">
								<button type="submit" class="am-btn am-btn-primary am-btn-block am-margin-left-0">导入</button>
							</div>
						</div>
					</div>
					<div class="am-u-sm-12 ">
						<a href="./Data/import.xlsx" class="am-text-default am-padding-left">模板下载</a>
					</div>
				</form>
				<!--over-->
			</div>
		</div>
	</form>
</div>
	<!--popup 导入-->
<div class="am-popup" id="pop_import_tuan">
	<form action="<?php echo U('import_tuan');?>" method="post" enctype="multipart/form-data">
		<div class="am-popup-inner">
			<div class="am-popup-hd">
				<h4 class="am-popup-title">外部团体批量导入</h4>
				<span data-am-modal-close class="am-close">&times;</span>
			</div>
			<div class="am-popup-bd">
				<!--begin-->
				<form class="am-form form-horizontal am-form-horizontal" method="post" action="" enctype="multipart/form-data">
					<div class="am-u-sm-12 am-margin-top-lg">
						<div class="am-u-sm-9">
							<div class="am-form-file am-text-left">
								<button type="button" class="am-btn am-btn-default am-btn-block am-margin-left-0">
									<span class="am-icon-cloud-upload"></span> 请选择要上传的文件
								</button>
								<input name="members" id="imports" type="file" >
							</div>
							<div id="file-list"></div>
						</div>
						<div class="am-u-sm-3">
							<div class="am-form-group am-text-left am-margin-left-0">
								<button type="submit" class="am-btn am-btn-primary am-btn-block am-margin-left-0">导入</button>
							</div>
						</div>
					</div>
					<div class="am-u-sm-12 ">
						<a href="./Data/import.xlsx" class="am-text-default am-padding-left">模板下载</a>
					</div>
				</form>
				<!--over-->
			</div>
		</div>
	</form>
</div>
	<!--popup 新增会员-->
<div class="am-popup" id="pop_add">
	<div class="am-popup-inner">
		<div class="am-popup-hd">
			<h4 class="am-popup-title">新增会员</h4>
			<span data-am-modal-close class="am-close">&times;</span>
		</div>
		<div class="am-popup-bd">
			<!--begin-->
			<form action="<?php echo U('index',array('type'=>'add'));?>" method="post" class="am-form am-form-horizontal form-horizontal">
				<div class="am-form-group">
					<label for="realname1" class="am-u-sm-3 am-form-label">姓名</label>

					<div class="am-u-sm-9">
						<input type="text" id="realname1" name="realname" placeholder="姓名">
						<small>输入会员的名字</small>
					</div>
				</div>

				<div class="am-form-group">
					<label for="company1" class="am-u-sm-3 am-form-label">公司名称</label>

					<div class="am-u-sm-9">
						<input type="text" id="company1" name="company" placeholder="公司名称">
						<small>输入公司的名称</small>
					</div>
				</div>

				<div class="am-form-group">
					<label for="dept1" class="am-u-sm-3 am-form-label">部门</label>

					<div class="am-u-sm-9">
						<input type="text" id="dept1" name="dept" placeholder="部门">
						<small>输入公司的部门</small>
					</div>
				</div>

				<div class="am-form-group">
					<label for="position1" class="am-u-sm-3 am-form-label">职位</label>

					<div class="am-u-sm-9">
						<input type="text" id="position1" name="position" placeholder="职位">
						<small>输入公司的职位</small>
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">性别</label>

					<div class="am-u-sm-9">
						<label class="am-radio-inline"><input type="radio" value="1" checked name="sex"> 男
						</label>
						<label class="am-radio-inline"><input type="radio" value="0" name="sex"> 女</label>
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">是否一线</label>

					<div class="am-u-sm-9">
						<label class="am-radio-inline"><input type="radio" value="1" name="is_onsite"> 是
						</label>
						<label class="am-radio-inline"><input type="radio" value="0" checked name="is_onsite"> 否</label>
					</div>
				</div>

				<div class="am-form-group">
					<label for="mobile1" class="am-u-sm-3 am-form-label">手机</label>

					<div class="am-u-sm-9">
						<input type="text" id="mobile1" name="mobile" maxlength="11" placeholder="输入会员的手机号码">
					</div>
				</div>

				<div class="am-form-group">
					<label for="id_num1" class="am-u-sm-3 am-form-label">身份证</label>

					<div class="am-u-sm-9">
						<input type="text" id="id_num1" name="id_num" maxlength="18" placeholder="输入会员的身份证号码">
					</div>
				</div>

				<div class="am-form-group">
					<label for="rank1" class="am-u-sm-3 am-form-label">会员等级</label>

					<div class="am-u-sm-9">
						<select id="rank1" name="rank">
							<option value="0">普通会员</option>
							<option value="1">航天内部会员</option>
							<option value="2">VIP会员</option>
						</select>
						<span class="am-form-caret"></span>
					</div>
				</div>
				<div class="am-form-group">
					<label for="level" class="am-u-sm-3 am-form-label">会员类别</label>

					<div class="am-u-sm-9">
						<select id="level" name="level">
							<option value=""></option>
							<option value="Ⅰ">Ⅰ</option>
							<option value="Ⅱ">Ⅱ</option>
							<option value="Ⅲ">Ⅲ</option>
							<option value="Ⅳ">Ⅳ</option>
							<option value="Ⅴ">Ⅴ</option>
							<option value="Ⅵ">Ⅵ</option>
							<option value="Ⅶ">Ⅶ</option>
						</select>
						<span class="am-form-caret"></span>
					</div>
				</div>
				<div class="am-form-group">
					<label for="married" class="am-u-sm-3 am-form-label">婚否</label>

					<div class="am-u-sm-9">
						<select id="married" name="married">
							<option value="不确定" selected>不确定</option>
							<option value="有">有</option>
							<option value="无">无</option>
						</select>
						<span class="am-form-caret"></span>
					</div>
				</div>


				<div class="am-form-group">
					<label for="service1" class="am-u-sm-3 am-form-label">服务</label>

					<div class="am-u-sm-9">
						<textarea class="" rows="3" name="service" id="service1" placeholder="输入服务"></textarea>
						<small>（含体检内容）</small>
					</div>
				</div>

				<div class="am-form-group">
					<div class="am-u-sm-9 am-u-sm-push-3">
						<button type="submit" class="am-btn am-btn-primary">保存</button>
					</div>
				</div>
			</form>
			<!--over-->
		</div>
	</div>
</div>
	<!--popup 编辑会员-->
<div class="am-popup" id="pop_edit">
	<div class="am-popup-inner">
		<div class="am-popup-hd">
			<h4 class="am-popup-title">编辑会员</h4>
			<span data-am-modal-close class="am-close">&times;</span>
		</div>
		<div class="am-popup-bd">
			<!--begin-->
			<form action="<?php echo U('index',array('type'=>'edit'));?>" method="post" class="am-form am-form-horizontal form-horizontal">
				<input type="hidden" name="id">

				<div class="am-form-group">
					<label for="realname2" class="am-u-sm-3 am-form-label">姓名</label>

					<div class="am-u-sm-9">
						<input type="text" id="realname2" name="realname" placeholder="姓名">
						<small>输入会员的名字</small>
					</div>
				</div>

				<div class="am-form-group">
					<label for="company2" class="am-u-sm-3 am-form-label">公司名称</label>

					<div class="am-u-sm-9">
						<input type="text" id="company2" name="company" placeholder="公司名称">
						<small>输入公司的名称</small>
					</div>
				</div>

				<div class="am-form-group">
					<label for="dept2" class="am-u-sm-3 am-form-label">部门</label>

					<div class="am-u-sm-9">
						<input type="text" id="dept2" name="dept" placeholder="部门">
						<small>输入公司的部门</small>
					</div>
				</div>

				<div class="am-form-group">
					<label for="position2" class="am-u-sm-3 am-form-label">职位</label>

					<div class="am-u-sm-9">
						<input type="text" id="position2" name="position" placeholder="职位">
						<small>输入公司的职位</small>
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">性别</label>

					<div class="am-u-sm-9">
						<label class="am-radio-inline"><input type="radio" value="1" name="sex"> 男
						</label>
						<label class="am-radio-inline"><input type="radio" value="0" name="sex"> 女</label>
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">是否一线</label>

					<div class="am-u-sm-9">
						<label class="am-radio-inline"><input type="radio" value="1" name="is_onsite"> 是
						</label>
						<label class="am-radio-inline"><input type="radio" value="0" name="is_onsite"> 否</label>
					</div>
				</div>

				<div class="am-form-group">
					<label for="mobile2" class="am-u-sm-3 am-form-label">手机</label>

					<div class="am-u-sm-9">
						<input type="text" id="mobile2" name="mobile" maxlength="11" placeholder="输入会员的手机号码">
					</div>
				</div>

				<div class="am-form-group">
					<label for="id_num2" class="am-u-sm-3 am-form-label">身份证</label>

					<div class="am-u-sm-9">
						<input type="text" id="id_num2" name="id_num" maxlength="18" placeholder="输入会员的身份证号码">
					</div>
				</div>

				<div class="am-form-group">
					<label for="rank2" class="am-u-sm-3 am-form-label">会员等级</label>

					<div class="am-u-sm-9">
						<select id="rank2" name="rank">
							<option value="0">普通会员</option>
							<option value="1">航天内部会员</option>
							<option value="2">VIP会员</option>
						</select>
						<span class="am-form-caret"></span>
					</div>
				</div>

				<div class="am-form-group">
					<label for="level" class="am-u-sm-3 am-form-label">会员类别</label>

					<div class="am-u-sm-9">
						<select id="level" name="level">
							<option value=""></option>
							<option value="Ⅰ">Ⅰ</option>
							<option value="Ⅱ">Ⅱ</option>
							<option value="Ⅲ">Ⅲ</option>
							<option value="Ⅳ">Ⅳ</option>
							<option value="Ⅴ">Ⅴ</option>
							<option value="Ⅵ">Ⅵ</option>
							<option value="Ⅶ">Ⅶ</option>
						</select>
						<span class="am-form-caret"></span>
					</div>
				</div>
				<div class="am-form-group">
					<label for="married" class="am-u-sm-3 am-form-label">婚否</label>

					<div class="am-u-sm-9">
						<select id="married" name="married">
							<option value="不确定">不确定</option>
							<option value="有">有</option>
							<option value="无">无</option>
						</select>
						<span class="am-form-caret"></span>
					</div>
				</div>


				<div class="am-form-group">
					<label for="service2" class="am-u-sm-3 am-form-label">服务</label>

					<div class="am-u-sm-9">
						<textarea class="" rows="3" name="service" id="service2" placeholder="输入服务"></textarea>
						<small>（含体检内容）</small>
					</div>
				</div>

				<div class="am-form-group">
					<div class="am-u-sm-9 am-u-sm-push-3">
						<button type="submit" class="am-btn am-btn-primary">保存</button>
					</div>
				</div>
			</form>
			<!--over-->
		</div>
	</div>
</div>

	<!--popup 绑定会员卡-->
<div class="am-popup" id="pop_open">
	<div class="am-popup-inner">
		<div class="am-popup-hd">
			<h4 class="am-popup-title">绑定会员卡</h4>
			<span data-am-modal-close class="am-close">&times;</span>
		</div>
		<div class="am-popup-bd">
			<!--begin-->
			<form action="<?php echo U('Member/newcard');?>" method="post" class="am-form am-form-horizontal form-horizontal">
				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">姓名</label>

					<div class="am-u-sm-9">
						<input type="text" readonly name="realname" placeholder="姓名">
						<input type="hidden" name="id">
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">刷卡</label>

					<div class="am-u-sm-9">
						<input type="text" name="card_num" autocomplete="off" required placeholder="请先放入卡片,再双击此处">
						<input type="hidden" name="card_sn" value="">
					</div>
				</div>

				<div class="am-form-group">
					<div class="am-u-sm-9 am-u-sm-push-3">
						<button type="submit" class="am-btn am-btn-primary">绑定</button>
					</div>
				</div>
			</form>
			<!--over-->
		</div>
	</div>
</div>
	<!--popup 充值-->
<div class="am-popup" id="pop_charge">
	<div class="am-popup-inner">
		<div class="am-popup-hd">
			<h4 class="am-popup-title">充值</h4>
			<span data-am-modal-close class="am-close">&times;</span>
		</div>
		<div class="am-popup-bd">
			<!--begin-->
			<form action="<?php echo U('Member/charge');?>" method="post" class="am-form form-horizontal am-form-horizontal">

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">刷卡</label>

					<div class="am-u-sm-9">
						<input type="text" name="card_num" autocomplete="off" required placeholder="请先放入卡片,再双击此处">
						<input type="hidden" name="card_sn" value="">
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">充值缘由</label>

					<div class="am-u-sm-9">
						<?php $last_charge_reason=session('last_charge_reason'); ?>
						<textarea name="reason"><?php echo ((isset($last_charge_reason) && ($last_charge_reason !== ""))?($last_charge_reason):'前台充值'); ?></textarea>
					</div>
				</div>


				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">充值金额</label>

					<div class="am-u-sm-9">
						<input type="number" name="money" required placeholder="请输入充值金额">
					</div>
				</div>

				<div class="am-form-group">
					<div class="am-u-sm-9 am-u-sm-push-3">
						<button type="submit" class="am-btn am-btn-primary">确认充值</button>
					</div>
				</div>
			</form>
			<!--over-->
		</div>
	</div>
</div>

	<!--popup 补卡-->
<div class="am-popup" id="pop_recard">
	<div class="am-popup-inner">
		<div class="am-popup-hd">
			<h4 class="am-popup-title">补卡</h4>
			<span data-am-modal-close class="am-close">&times;</span>
		</div>
		<div class="am-popup-bd">
			<!--begin-->
			<form action="<?php echo U('Member/recard');?>" method="post" class="am-form am-form-horizontal form-horizontal">
				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">姓名</label>

					<div class="am-u-sm-9">
						<input type="text" readonly name="realname" placeholder="姓名">
						<input type="hidden" name="id">
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">刷卡</label>

					<div class="am-u-sm-9">
						<input type="text" name="card_num" autocomplete="off" placeholder="请先放入卡片,再双击此处">
						<input type="hidden" name="card_sn" value="">
					</div>
				</div>

				<div class="am-form-group">
					<div class="am-u-sm-9 am-u-sm-push-3">
						<button type="submit" class="am-btn am-btn-primary">绑定</button>
					</div>
				</div>
			</form>
			<!--over-->
		</div>
	</div>
</div>

	<!--popup 充值-->
<div class="am-popup" id="pop_froze_money">
	<div class="am-popup-inner">
		<div class="am-popup-hd">
			<h4 class="am-popup-title">押金</h4>
			<span data-am-modal-close class="am-close">&times;</span>
		</div>
		<div class="am-popup-bd">
			<!--begin-->
			<form action="<?php echo U('Member/lockmoney');?>" method="post" class="am-form am-form-horizontal form-horizontal">

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">刷卡</label>

					<div class="am-u-sm-9">
						<input type="text" name="card_num" autocomplete="off" required placeholder="请先放入卡片,再双击此处">
						<input type="hidden" name="card_sn" value="">
					</div>

				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">姓名</label>

					<div class="am-u-sm-9">
						<input type="text" name="realname" readonly placeholder="刷卡后获取姓名">
						<input type="hidden" name="id">
					</div>

				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">可用余额</label>

					<div class="am-u-sm-9">
						<input type="text" name="balance" readonly placeholder="刷卡后获取余额">
					</div>

				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">已冻结部分</label>

					<div class="am-u-sm-9">
						<input type="text" name="frozen_balance" readonly placeholder="刷卡后获取">
					</div>

				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">押金金额</label>

					<div class="am-u-sm-9">
						<input type="text" name="money" required placeholder="请输入押金金额">
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">密码</label>

					<div class="am-u-sm-9">
						<input type="password" name="password" required placeholder="请输入密码">
					</div>
				</div>


				<div class="am-form-group">
					<div class="am-u-sm-9 am-u-sm-push-3">
						<button type="submit" class="am-btn am-btn-primary">冻结</button>
					</div>
				</div>

				<hr>
				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">输入解冻金额</label>

					<div class="am-u-sm-9">
						<input type="number" name="release_money" placeholder="输入解冻金额">
					</div>

				</div>
				<div class="am-form-group">
					<div class="am-u-sm-9 am-u-sm-push-3">
						<button type="button" id="release_btn" class="am-btn am-btn-primary">解冻</button>
					</div>
				</div>
			</form>
			<!--over-->
		</div>
	</div>
</div>

	<!--popup 消费-->
<div class="am-popup" id="pop_consume">
	<div class="am-popup-inner">
		<div class="am-popup-hd">
			<h4 class="am-popup-title">消费</h4>
			<span data-am-modal-close class="am-close">&times;</span>
		</div>
		<div class="am-popup-bd">
			<!--begin-->
			<form class="am-form form-horizontal am-form-horizontal" action="<?php echo U('Member/consume');?>" method="post">
				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">刷卡</label>

					<div class="am-u-sm-9">
						<input type="text" name="card_num" autocomplete="off" required placeholder="请先放入卡片,再双击此处">
						<input type="hidden" name="card_sn" value="">
					</div>

				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">姓名</label>

					<div class="am-u-sm-9">
						<input type="text" name="realname" readonly placeholder="刷卡后获取姓名">
						<input type="hidden" name="id">
					</div>

				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">可用余额</label>

					<div class="am-u-sm-9">
						<input type="text" name="balance" readonly placeholder="刷卡后获取余额">
					</div>

				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">已冻结部分</label>

					<div class="am-u-sm-9">
						<input type="text" name="frozen_balance" readonly placeholder="刷卡后获取">
					</div>

				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">消费金额</label>

					<div class="am-u-sm-9">
						<input type="text" name="money" required placeholder="请输入消费金额">
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">密码</label>

					<div class="am-u-sm-9">
						<input type="password" name="password" required placeholder="请输入密码">
					</div>
				</div>
				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">消费类别</label>

					<div class="am-u-sm-9">
						<select name="type">
							<?php if(is_array($consume_type)): $i = 0; $__LIST__ = $consume_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$type): $mod = ($i % 2 );++$i;?><option value="<?php echo ($type); ?>"><?php echo ($type); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</div>
				</div>


				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">缘由</label>

					<div class="am-u-sm-9">
						<textarea name="reason"><?php echo session('last_reason');?></textarea>
					</div>
				</div>


				<div class="am-form-group">
					<div class="am-u-sm-9 am-u-sm-push-3">
						<button type="submit" class="am-btn am-btn-primary">确定</button>
					</div>
				</div>
			</form>
			<!--over-->
		</div>
	</div>
</div>
	<!--popup 会员信息-->
<div class="am-popup" id="pop_info">
	<div class="am-popup-inner">
		<div class="am-popup-hd">
			<h4 class="am-popup-title">会员信息</h4>
			<span data-am-modal-close class="am-close">&times;</span>
		</div>
		<div class="am-popup-bd">
			<!--begin-->
			<form class="am-form form-horizontal am-form-horizontal">
				<div class="am-form-group">
					<div class="am-u-sm-6">
						<label class="am-form-label">姓名</label>
						<label class="am-text-secondary val realname">刘云</label>
					</div>
					<div class="am-u-sm-6">
						<label class="am-form-label">性别</label>
						<label class="am-text-secondary val sex">女</label>
					</div>
				</div>
				<div class="am-form-group">
					<div class="am-u-sm-6">
						<label class="am-form-label">会员卡号</label>
						<label class="am-text-secondary val card_num">HT20150001</label>
					</div>
					<div class="am-u-sm-6">
						<label class="am-form-label">单位</label>
						<label class="am-text-secondary val company">无锡市文创有限公司</label>
					</div>
				</div>
				<div class="am-form-group">
					<div class="am-u-sm-6">
						<label class="am-form-label">部门</label>
						<label class="am-text-secondary val dept">财务部</label>
					</div>
					<div class="am-u-sm-6">
						<label class="am-form-label">职位</label>
						<label class="am-text-secondary val position">高级总监</label>
					</div>
				</div>
				<div class="am-form-group">
					<div class="am-u-sm-6">
						<label class="am-form-label">身份证</label>
						<label class="am-text-secondary val id_num">32016197608231245</label>
					</div>
					<div class="am-u-sm-6">
						<label class="am-form-label">手机</label>
						<label class="am-text-secondary val mobile">13402506988</label>
					</div>
				</div>
				<hr>
				<div class="am-form-group">
					<div class="am-u-sm-6">
						<label class="am-form-label">会员等级</label>
						<label class="am-text-warning val rank">金卡</label>
					</div>
					<div class="am-u-sm-6">
						<label class="am-form-label">金额</label>
						<label class="am-text-secondary val balance">8000</label>
					</div>
				</div>
				<hr>
				<div class="am-form-group">
					<div class="am-u-sm-6">
						<label class="am-form-label">会员类别</label>
						<label class="am-text-secondary val level">I</label>
					</div>
					<div class="am-u-sm-6">
						<label class="am-form-label">婚否</label>
						<label class="am-text-secondary val married">不确定</label>
					</div>
				</div>
				<hr>
				<div class="am-form-group">
					<h4 class="am-text-center">消费记录</h4>
					<table class="am-table am-table-striped am-table-hover am-table-compact am-text-nowrap data-table">
					</table>
				</div>
				<!--
				<div class="am-form-group">
					<div class="am-u-sm-12">
						<button type="button" class="am-btn am-btn-primary ">确定</button>
					</div>
				</div>
				-->
			</form>
			<!--over-->
		</div>
	</div>
</div>

	<!--popup 重置密码-->
<div class="am-popup" id="pop_repass">
	<div class="am-popup-inner">
		<div class="am-popup-hd">
			<h4 class="am-popup-title">重置密码</h4>
			<span data-am-modal-close class="am-close">&times;</span>
		</div>
		<div class="am-popup-bd">
			<!--begin-->
			<form action="<?php echo U('Member/repass');?>" method="post" class="am-form am-form-horizontal form-horizontal">

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">刷卡</label>

					<div class="am-u-sm-9">
						<input type="text" name="card_num" autocomplete="off" required placeholder="请先放入卡片,再双击此处">
						<input type="hidden" name="card_sn" value="">
					</div>

				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">姓名</label>

					<div class="am-u-sm-9">
						<input type="text" name="realname" readonly placeholder="刷卡后获取姓名">
						<input type="hidden" name="id">
					</div>

				</div>

				<!--<div class="am-form-group">-->
				<!--<label class="am-u-sm-3 am-form-label">输入原密码</label>-->

				<!--<div class="am-u-sm-9">-->
				<!--<input type="password" name="password0" minlength="6" required placeholder="请输入旧密码">-->
				<!--</div>-->
				<!--</div>-->

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">输入新密码</label>

					<div class="am-u-sm-9">
						<input type="password" name="password1" minlength="6" required placeholder="请输入新密码">
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">再次输入新密码</label>

					<div class="am-u-sm-9">
						<input type="password" name="password" minlength="6" required placeholder="再次请输入新密码">
					</div>
				</div>


				<div class="am-form-group">
					<div class="am-u-sm-9 am-u-sm-push-3">
						<button type="submit" class="am-btn am-btn-primary">重置密码</button>
					</div>
				</div>

			</form>
			<!--over-->
		</div>
	</div>
</div>

	<!--popup 批量编辑-->
<div class="am-popup" id="pop_bat_edit">
	<div class="am-popup-inner">
		<div class="am-popup-hd">
			<h4 class="am-popup-title">批量编辑</h4>
			<span data-am-modal-close class="am-close">&times;</span>
		</div>
		<div class="am-popup-bd">
			<!--begin-->
			<form action="<?php echo U('Member/index',array('type'=>'bat_edit'));?>" method="post" class="am-form am-form-horizontal form-horizontal">

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">您选择了:</label>

					<div class="am-u-sm-9 selected_person am-padding-top-xs">
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">请选择操作字段:</label>

					<div class="am-u-sm-9">
						<select id="bat_edit_type">
							<option value="company">单位</option>
							<option value="dept">部门</option>
							<option value="position">职位</option>
							<option value="is_onsite">是否一线</option>
							<option value="rank">等级</option>
							<option value="level">类型</option>
						</select>
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">请键入操作后的值:</label>

					<div class="am-u-sm-9 bat_value_div">
						<input type="text" id="bat_value" required placeholder="请输入值">
					</div>
				</div>

				<div class="am-form-group">
					<div class="am-u-sm-9 am-u-sm-push-3">
						<button type="submit" class="am-btn am-btn-primary">确认</button>
					</div>
				</div>
			</form>
			<!--over-->
		</div>
	</div>
</div>
	<!--popup 批量编辑金额-->
<div class="am-popup" id="pop_balance">
	<div class="am-popup-inner">
		<div class="am-popup-hd">
			<h4 class="am-popup-title">金额调整</h4>
			<span data-am-modal-close class="am-close">&times;</span>
		</div>
		<div class="am-popup-bd">
			<!--begin-->
			<form action="<?php echo U('Member/balance');?>" method="post" class="am-form am-form-horizontal form-horizontal">

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">您选择了:</label>

					<div class="am-u-sm-9 selected_person am-padding-top-xs">
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">金额变化:</label>

					<div class="am-u-sm-9">
						<select name="balance_type">
							<option value="+">增加</option>
							<option value="-">减少</option>
						</select>
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">操作理由</label>

					<div class="am-u-sm-9">
						<select name="reason">
							<?php if(is_array($balanceReasons)): $i = 0; $__LIST__ = $balanceReasons;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$reason): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($reason); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</div>
				</div>

				<div class="am-form-group">
					<label class="am-u-sm-3 am-form-label">请输入值:</label>

					<div class="am-u-sm-9 bat_value_div">
						<input type="text" name="value" required placeholder="请输入金额">
					</div>
				</div>

				<div class="am-form-group">
					<div class="am-u-sm-9 am-u-sm-push-3">
						<button type="submit" class="am-btn am-btn-primary">确认</button>
					</div>
				</div>
			</form>
			<!--over-->
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
	<script type="text/javascript" src="/Public/static/amaze/js/fnReloadAjax.js"></script>
	<script type="text/javascript" src="/Public/Admin/js/common.js"></script>
	<script type="text/javascript" src="/Public/Admin/js/member_index.js"></script>

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