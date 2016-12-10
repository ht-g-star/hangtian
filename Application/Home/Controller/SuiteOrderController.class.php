<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 Todd All rights reserved.
// +----------------------------------------------------------------------
// |  官方网址: vboshi.cn
// +----------------------------------------------------------------------
namespace Home\Controller;

use Think\Controller;
use Think\Page;

/* 预约管理控制器*/

class SuiteOrderController extends HomeController {

	public function _initialize() {
		parent::_initialize();
		//自动检测已经付款订单
		$pre = C("DB_PREFIX");

		$_list = M("suite_order")->alias("o")->field("o.id")->join("left join {$pre}pay p on p.out_trade_no=o.order_no")->where("p.status=1 and o.status=1")->select();
		$ids   = array ();
		foreach ($_list as $_row) {
			$ids[] = $_row['id'];
			R("Api/SuiteOrderSend/send", array ('order_id' => $_row['id']));
		}
		if (!empty($ids)) {
			M("suite_order")->where(array ("id" => array ("IN", $ids)))->setField("status", SuiteController::ORDER_STATUS_OK);
		}
	}

	/* 预约管理首页*/
	public function index() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();

		$this->assign('uid', $uid);

		/* 数据分页*/
		$map['uid']   = $uid;
		$map['total'] = array ('gt', 0);;
		$list = D("order")->getLists($map);
		$this->assign('list', $list);// 赋值数据集
		$page = D("order")->getPage($map);
		$this->assign('page', $page);//

		/*优惠券数量*/
		$num = M("UserCoupon")->where("uid='$uid'")->count();
		$this->assign('num', $num);
		/*购物车中数量*/
		$shopnum = M("shopcart")->where("uid='$uid'")->count();
		$this->assign('shopnum', $shopnum);
		/*待支付*/
		$onum = M("order")->where("uid='$uid' and status='-1' and ispay='1'")->count();
		$this->assign('onum', $onum);
		/*待发货*/
		$dnum = M("order")->where("uid='$uid' and status='1'")->count();
		$this->assign('dnum', $dnum);
		/*待确认*/
		$cnum = M("order")->where("uid='$uid' and status='2'")->count();
		$this->assign('cnum', $cnum);
		/*最后一次登录时间*/
		$time = M("member")->where("uid='$uid'")->limit(1)->find();
		$this->assign('time', $time);
		
		//站内信数量
		$ecount = D("UserEnvelope")->getNum($uid);
		$this->assign('ecount', $ecount);

		$this->meta_title = get_username() . '的个人中心';
		$this->display();
	}
	
	public function reason() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$this->display();

	}

	/*****全部预约****/
	public function allorder() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();

		//自动发货
		$this->auto_express($uid);
		
		$db            = M("suite_order");
		$map['o.c_id'] = $uid;
		$pre           = C("DB_PREFIX");
		$join          = " left join " . $pre . "suite s on s.id=o.s_id";

		$count = $db->alias('o')->field('id')->where($map)->count();
		$page  = new  Page($count, 10);

		$list = $db->alias("o")->field("s.title,o.id,o.ctime,o.count,o.cost,o.status,o.order_no")->limit($page->firstRow . ',' . $page->listRows)->order('o.id desc')->where($map)->join($join)->select();
		if (empty($list)) {
			$list = array ();
		}
		$this->assign('list', $list);// 赋值数据集
		$this->assign('page', $page->show());// 
		$this->meta_title = '我的所有预约';
		$this->display();
	}

	/* 待支付预约*/
	public function needpay() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}

		$uid = is_login();

		/* 数据分页*/
		$db              = M("suite_order");
		$map['o.c_id']   = $uid;
		$map['o.status'] = SuiteController::ORDER_STATUS_WAITING_PAY;
		$pre             = C("DB_PREFIX");
		$join            = " left join " . $pre . "suite s on s.id=o.s_id";
		$list            = $db->alias("o")->field("s.title,o.id,o.ctime,o.count,o.cost,o.status,o.order_no")->where($map)->join($join)->order("id desc")->select();
		if (empty($list)) {
			$list = array ();
		}
		$count = $db->alias('o')->field('id')->where($map)->count();
		$page  = new  Page($count, 10);


		$this->assign('list', $list);// 赋值数据集
		$this->assign('page', $page->show());// 
		$this->meta_title = '待支付预约';
		$this->display();
	}

	/* 待确认预约*/
	public function tobeconfirmed() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();
		
		//自动发货
		$this->auto_express($uid);
		/* 数据分页*/
		/* 数据分页*/
		$db              = M("suite_order");
		$map['o.c_id']   = $uid;
		$map['o.status'] = SuiteController::ORDER_STATUS_OK;
		$pre             = C("DB_PREFIX");
		$join            = " left join " . $pre . "suite s on s.id=o.s_id";
		$list            = $db->alias("o")->field("s.title,o.id,o.ctime,o.count,o.cost,o.status,o.order_no")->where($map)->join($join)->select();
		if (empty($list)) {
			$list = array ();
		}

		$count = $db->alias('o')->field('id')->where($map)->count();
		$page  = new  Page($count, 20);


		$this->assign('list', $list);// 赋值数据集
		$this->assign('page', $page->show());// 

		$this->meta_title = '待体检预约';
		$this->display();
	}

	public function tobeuse() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();

		//自动发货
		$this->auto_express($uid);
		/* 数据分页*/
		/* 数据分页*/
		$db              = M("suite_order");
		$map['o.c_id']   = $uid;
		$map['o.status'] = SuiteController::ORDER_STATUS_MARK_REG;
		$pre             = C("DB_PREFIX");
		$join            = " left join " . $pre . "suite s on s.id=o.s_id";
		$list            = $db->alias("o")->field("s.title,o.id,o.ctime,o.count,o.cost,o.status,o.order_no")->where($map)->join($join)->select();
		if (empty($list)) {
			$list = array ();
		}
		$count = $db->alias('o')->field('id')->where($map)->count();
		$page  = new  Page($count, 20);


		$this->assign('list', $list);// 赋值数据集
		$this->assign('page', $page->show());// 
		$this->assign('page', $page);// 
		$this->meta_title = '待体检预约';
		$this->display();
	}

	public function finished() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();

		//自动发货
		$this->auto_express($uid);
		/* 数据分页*/
		/* 数据分页*/
		$db              = M("suite_order");
		$map['o.c_id']   = $uid;
		$map['o.status'] = SuiteController::ORDER_STATUS_FINISH;
		$pre             = C("DB_PREFIX");
		$join            = " left join " . $pre . "suite s on s.id=o.s_id";
		$list            = $db->alias("o")->field("s.title,o.id,o.ctime,o.count,o.cost,o.status,o.order_no")->where($map)->join($join)->select();
		if (empty($list)) {
			$list = array ();
		}
		$count = $db->alias('o')->field('id')->where($map)->count();
		$page  = new  Page($count, 20);


		$this->assign('list', $list);// 赋值数据集
		$this->assign('page', $page->show());// 
		$this->assign('page', $page);// 
		$this->meta_title = '待体检预约';
		$this->display();
	}


	public function auto_express($uid) {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		if (empty($uid)) {
			$uid = is_login();
		}
		//判断是否开启自动发货
		if (1 == C('AUTO_SEND')) {
			$Express       = M("Express");
			$map['status'] = 1;
			$map['uid']    = $uid;
			$list          = M("order")->where($map)->select();
			//判断快递单号库存是否充足
			$condition['status'] = 1;
			$data                = $Express->where($condition)->order('id desc')->select();
			if ($list && !$data) {
				addUserLog('快递单号库存不足，请尽快补充', $uid, true);
			}
			if ($list) {
				foreach ($list as $n => $v) {
					$data['status']       = 2;
					$condition2['status'] = 1;
					$id                   = $v['id'];
					$orderid              = $v['orderid'];
					$info                 = $Express->where($condition2)->order('id desc')->limit(1)->find();
					if ($info) {
						$data['express']      = $info['title'];
						$data['express_code'] = $info['code'];
						$data['send_name']    = C('SHOPNAME');
						$data['send_contact'] = C('CONTACT');
						$data['send_address'] = C('ADDRESS');
						$data['create_time']  = NOW_TIME;
						M("order")->where("id='$id'")->save($data);
						M("Express")->where("id='$info[id]'")->setField('status', 2);
						addUserLog('自动发货成功，预约号' . $orderid, $uid, true);
					}
				}
			}
		}
	}

	public function detail($id) {
		$order            = M("suite_order")->find($id);
		$this->meta_title = "体检预约详情";
		//查询pdf

		$this->assign("order", $order);
		$period = M("suite_period")->find($order['p_id']);
		$this->assign("period", $period);
		$order_info = M("suite_order_info")->where("o_id=%d", $order['id'])->select();
		$this->assign("list", $order_info);
		$this->display();
	}

	public function down($o_id, $id_num) {
		$order = M("suite_order")->find($o_id);
		if (!$order) {
			$this->error("订单不存在!");
		} else {
			$reports = M("report")->where("order_no='%s' and id_num='%s'", $order['order_no'], $id_num)->select();

			$filename = $reports['path'];
			if (file_exists($filename)) {
				header('Content-type: application/pdf');
//				header("Content-Disposition: attachment; filename=" . basename($filename));
				readfile($filename);
				exit;
			} else {
				$this->error("此订单报告暂时不存在,您可以去报告历史查询.",U('Home/Report/index'));
			}
		}
	}

	public function comment($id) {
		$order            = M("suite_order")->find($id);
		$this->meta_title = "评价订单";
		$this->assign("order", $order);
		$period = M("suite_period")->find($order['p_id']);
		$this->assign("period", $period);

		$db      = M("suite_comment");
		$comment = $db->where("o_id=%d and c_id=%d and s_id=%d", $order['id'], $order['c_id'], $order['s_id'])->find();
		$this->assign("comment", $comment);
		if (IS_POST) {
			$db->add(array (
				         "s_id"    => $order['s_id'],
				         "c_id"    => $order['c_id'],
				         "o_id"    => $order['id'],
				         "status"  => 0,//审核
				         "content" => I("post.content"),
				         "stars"   => I("post.stars"),
				         "ctime"   => time()
			         ));
			$this->success("评价成功,审核后即可显示.");
			return;
		}
		$this->display();
	}
}
