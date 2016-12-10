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
use Common\Api\GatApi;

/* 会员中心控制器*/

class CenterController extends HomeController {
	/* 会员中心首页*/
	public function index() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();

		//自动发货
		$this->auto_express($uid);
		$this->assign('uid', $uid);
//
//		/* 数据分页*/
//		$map['uid']   = $uid;
//		$map['total'] = array ('gt', 0);;
//		$list = D("order")->getLists($map);
//		$this->assign('list', $list);// 赋值数据集
//		$page = D("order")->getPage($map);
//		$this->assign('page', $page);//
//
//		/*优惠券数量*/
//		$num = M("UserCoupon")->where("uid='$uid'")->count();
//		$this->assign('num', $num);
//		/*购物车中数量*/
//		$shopnum = M("shopcart")->where("uid='$uid'")->count();
//		$this->assign('shopnum', $shopnum);
//		/*待支付*/
		$onum = M("order")->where("uid='$uid' and status='-1' and ispay='1'")->count();
		$this->assign('onum', $onum);
//		/*待发货*/
		$dnum = M("order")->where("uid='$uid' and status='1'")->count();
		$this->assign('dnum', $dnum);
//		/*待确认*/
		$cnum = M("order")->where("uid='$uid' and status='2'")->count();
		$this->assign('cnum', $cnum);

		//收藏
		$ccnum = M("collect")->where("uid='$uid'")->count();
		$this->assign('ccnum', $ccnum);
//		/*最后一次登录时间*/
//		$time = M("member")->where("uid='$uid'")->limit(1)->find();
//		$this->assign('time', $time);
//
//		//站内信数量
//		$ecount = D("UserEnvelope")->getNum($uid);
//		$this->assign('ecount', $ecount);
		$user = session("user_auth");
		$user = M("customer")->find($user['id']);
		$this->assign("user", $user);
		$this->meta_title = $user['realname'] . '的个人中心';

		//查关爱积分
		$customerInfo = M("customer")->find($user['id']);
		$jifen_result = GatApi::jifen_get_employee($customerInfo['id']);
		$jifen_balance = 0;
		if(isset($jifen_result['balance'])) {
			$jifen_balance = $jifen_result['balance'];
		}

		$id = $user['id'];
		$white_list_single = array (
				//航天内部+关爱通会员
				13062, 6431, 4031, 7511, 4180, 3999,22553,22546,22547,
				//上海动力所
				7084,7085,7101,14571,16101,16103,16104,20441,20495,21172,22577,
				//湖州
				16098,16099,16100,16545,21025,
				//新会员
				27715
		);
		if(in_array($id, $white_list_single) || ($id >= 22650 && $id <= 23059 ) || ($id >= 19984 && $id <= 20430))
		{
			$in_list = 1;
		} else {
			$in_list = 2;
		}
		$this->assign('in_list', $in_list);

		$this->assign('gatjf_balance', $jifen_balance);
		$this->display();
	}
	
	public function reason() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$this->display();

	}

	/*****全部订单****/
	public function allorder() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();

		//自动发货
		$this->auto_express($uid);
		
		/* 数据分页*/
		$map['uid']   = $uid;
		$map['total'] = array ('gt', 0);
		$map['ordertype'] = array("eq", 1);
		$list = D("order")->getLists($map);
		$this->assign('list', $list);// 赋值数据集
		$page = D("order")->getPage($map);
		$this->assign('page', $page);//
		$this->meta_title = '我的所有订单';
		$this->display();
	}

	/* 待支付订单*/
	public function needpay() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}

		$uid = is_login();
		//自动发货
		$this->auto_express($uid);
		
		/* 数据分页*/
		$map['uid']    = $uid;
		$map['status'] = -1;
		$map['ispay']  = 1;
		$map['ordertype'] = array("eq", 1);
		$list          = D("order")->getLists($map);
		if (empty($list)) {
			$list = array ();
		}
		$this->assign('list', $list);// 赋值数据集
		$page = D("order")->getPage($map);
		$this->assign('page', $page);// 
		$this->meta_title = '待支付订单';
		$this->display();
	}

	/* 待发货订单*/
	public function tobeshipped() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();
		
		//自动发货
		$this->auto_express($uid);
		/* 数据分页*/
		$map['uid']    = $uid;
		$map['status'] = 1;
		$map['ordertype'] = array("eq", 1);
		$list          = D("order")->getLists($map);
		$this->assign('list', $list);// 赋值数据集
		$page = D("order")->getPage($map);
		$this->assign('page', $page);// 
		$this->assign('list', $list);
		$this->meta_title = '待发货订单';
		$this->display();
	}

	/* 待确认订单*/
	public function tobeconfirmed() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();
		//自动发货
		$this->auto_express($uid);
		
		$map['uid']    = $uid;
		$map['status'] = 2;
		$map['ordertype'] = array("eq", 1);
		$list          = D("order")->getLists($map);
		$this->assign('list', $list);// 赋值数据集
		$page = D("order")->getPage($map);
		$this->assign('page', $page);// 
		$this->meta_title = '待确认订单';
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
						addUserLog('自动发货成功，订单号' . $orderid, $uid, true);
					}
				}
			}
		}
	}
}
