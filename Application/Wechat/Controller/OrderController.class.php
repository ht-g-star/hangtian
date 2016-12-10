<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 Todd All rights reserved.
// +----------------------------------------------------------------------
// |  官方网址: vboshi.cn
// +----------------------------------------------------------------------
namespace Wechat\Controller;

use Home\Controller\SuiteController;
use Think\Controller;
use Think\Page;

/* 订单管理控制器*/

class OrderController extends FollowerController {

	protected $user;
	protected $openid;


	protected function _initialize() {
		parent::_initialize();
		$this->openid = get_openid();
		if (session("user_auth")) {
			$this->user = M('customer')->where("openid='%s'", $this->openid)->find();
			session("user_auth", $this->user);
			return $this->user;
		} else {
			$this->user = M('customer')->where("openid='%s'", $this->openid)->find();
			if (!$this->user) {
				$this->redirect('Public/login');
			} else {
				session("user_auth", $this->user);
			}
			return $this->user;
		}
	}


	/* 订单管理首页*/
	public function index() {
		$this->allorder();
	}
	
	public function reason() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$this->display();
	}

	public function detail($oid) {
		$pre    = C("DB_PREFIX");
		$order  = M("suite_order")->find($oid);
		$suite  = M("suite")->find($order['s_id']);
		$period = M("suite_period")->find($order['p_id']);
		$this->assign("suite", $suite);
		$this->assign("order", $order);
		$this->assign("period", $period);
		$info             = M("suite_order_info")->where("o_id=%d and p_id=%d", $order['id'], $order['p_id'])->select();
		$this->meta_title = '订单详情';
		$this->assign("info", $info);
		$this->display();
	}

	/*****全部订单****/
	public function allorder() {
		$db            = M("suite_order");
		$map['o.c_id'] = $this->user['id'];
		$pre           = C("DB_PREFIX");
		$join          = " left join " . $pre . "suite s on s.id=o.s_id";

		$count = $db->alias('o')->field('id')->where($map)->count();
		$page  = new  Page($count, 10);

		$list = $db->alias("o")->field("s.id as s_id,s.price_sex_0,s.price_sex_1,s.title,o.id,o.order_no,o.ctime,o.count,o.cost,o.status")->limit($page->firstRow . ',' . $page->listRows)->where($map)->join($join)->select();
		if (empty($list)) {
			$list = array ();
		}
		$this->assign('list', $list);// 赋值数据集
		$this->assign('page', $page->show());// 
		$this->meta_title = '我的所有订单';
		$this->display("allorder");
	}

	/* 待支付订单*/
	public function needpay() {

		$uid = is_login();

		/* 数据分页*/
		$db              = M("suite_order");
		$map['o.c_id']   = $uid;
		$map['o.status'] = SuiteController::ORDER_STATUS_WAITING_PAY;
		$pre             = C("DB_PREFIX");
		$join            = " left join " . $pre . "suite s on s.id=o.s_id";
		$list            = $db->alias("o")->field("s.title,o.id,o.ctime,o.count,o.cost,o.status")->where($map)->join($join)->select();
		if (empty($list)) {
			$list = array ();
		}
		$count = $db->alias('o')->field('id')->where($map)->count();
		$page  = new  Page($count, 10);


		$this->assign('list', $list);// 赋值数据集
		$this->assign('page', $page->show());// 
		$this->meta_title = '待支付订单';
		$this->display();
	}

	/* 待确认订单*/
	public function tobeconfirmed() {
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
		$list            = $db->alias("o")->field("s.title,o.id,o.ctime,o.count,o.cost,o.status")->where($map)->join($join)->select();
		if (empty($list)) {
			$list = array ();
		}

		$count = $db->alias('o')->field('id')->where($map)->count();
		$page  = new  Page($count, 20);


		$this->assign('list', $list);// 赋值数据集
		$this->assign('page', $page->show());// 

		$this->meta_title = '待体检订单';
		$this->display();
	}

	public function tobeuse() {
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
		$list            = $db->alias("o")->field("s.title,o.id,o.ctime,o.count,o.cost,o.status")->where($map)->join($join)->select();
		if (empty($list)) {
			$list = array ();
		}
		$count = $db->alias('o')->field('id')->where($map)->count();
		$page  = new  Page($count, 20);


		$this->assign('list', $list);// 赋值数据集
		$this->assign('page', $page->show());// 
		$this->assign('page', $page);// 
		$this->meta_title = '待体检订单';
		$this->display();
	}

	public function finished() {
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
		$list            = $db->alias("o")->field("s.title,o.id,o.ctime,o.count,o.cost,o.status")->where($map)->join($join)->select();
		if (empty($list)) {
			$list = array ();
		}
		$count = $db->alias('o')->field('id')->where($map)->count();
		$page  = new  Page($count, 20);


		$this->assign('list', $list);// 赋值数据集
		$this->assign('page', $page->show());// 
		$this->assign('page', $page);// 
		$this->meta_title = '待体检订单';
		$this->display();
	}


	public function auto_express($uid) {
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
				addUserLog('快递单号库存不足，请尽快补充', $uid,true);
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
						addUserLog('自动发货成功，订单号' . $orderid, $uid,true);
					}
				}
			}
		}
	}
}
