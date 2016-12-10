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

class UserMallOrderController extends FollowerController {
	protected $user;
	protected $openid;
	
	protected function _initialize() {
		parent::_initialize();
		$this->openid = get_openid();
		
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
	
	/*****全部订单****/
	public function allorder() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();
		$this->assign("custommoney", M("customer")->where("id=%d", $uid)->getField("balance"));
		//自动发货
		$this->auto_express($uid);
		
		/* 数据分页*/
		$map['uid'] = $uid;
		$ordertype  = 1;
		// $ordertype = I('ordertype');
		if ($ordertype) {
			$map['ordertype'] = $ordertype;
			$this->assign('ordertype', $ordertype);
		}
		$map['total'] = array ('gt', 0);;
		$list = D("Home/Order")->getLists($map);
		$this->assign('list', $list);// 赋值数据集
		$page = D("Home/Order")->getPage($map);
		$this->assign('page', $page);//
		$this->meta_title = '我的所有订单';
		$this->display("allorder");
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
		$list          = D("Home/Order")->getLists($map);
		if (empty($list)) {
			$list = array ();
		}
		$this->assign('list', $list);// 赋值数据集
		$page = D("Home/Order")->getPage($map);
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
		$map['status'] = 1;;
		$list = D("Home/Order")->getLists($map);
		$this->assign('list', $list);// 赋值数据集
		$page = D("Home/Order")->getPage($map);
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
		$list          = D("Home/Order")->getLists($map);
		$this->assign('list', $list);// 赋值数据集
		$page = D("Home/Order")->getPage($map);
		$this->assign('page', $page);// 
		$this->meta_title = '我的收藏';
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
	
	/* 文档模型频道页 */
	public function detail($id) {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$id = I('get.id');//获取id
		$id = safe_replace($id);//过滤
		
		$typeCom = M("order")->where("orderid='$id'")->getField("express");
		$typeNu  = M("order")->where("orderid='$id'")->getField("express_code");
		
		$ordertype = M("order")->where("orderid='$id'")->getField("ordertype");
		
		if (isset($typeCom) && $typeNu) {
			$retData = $this->getkuaidi($typeCom, $typeNu);
		} else {
			$retData = "";
		}
		$this->assign('kuaidata', $retData);
		/* uid调用*/
		$uid    = is_login();
		$order  = D("order");
		$detail = $order->where("orderid='$id'")->select();
		$list   = M("shoplist");
		foreach ($detail as $n => $val) {
			$detail[ $n ]['id'] = $list->where('orderid=\'' . $val['id'] . '\'')->select();
		}
		$addressid = $order->where("orderid='$id'")->getField("addressid");
		$trans     = M("address")->where("id='$addressid'")->select();
		
		
		$cancel = M("cancel")->where("orderid='{$detail[0]['orderid']}'")->find();
		$this->assign('cancel', $cancel);
		
		if ($ordertype == 4) {
			$jipiao = M("jipiao_order")->where(array ("orderid" => $id, "isactive" => 1))->find();
		}
		
		if ($jipiao) {
			$this->assign("jipiao", $jipiao);
		}
		
		$this->assign("ordertype1", $ordertype);
		$this->assign('translist', $trans);
		$this->assign('detaillist', $detail);
		$this->assign('ordertype', $detail[0]);
		$this->meta_title = '订单详情';
		$this->display();
	}
	
	/*****酒店订单****/
	public function hotel() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();
		$this->assign("custommoney", M("customer")->where("id=%d", $uid)->getField("balance"));
		//自动发货
		//$this->auto_express($uid);
		
		/* 数据分页*/
		$map['ordertype'] = 2;
		$map['uid']       = $uid;
		$map['total']     = array ('gt', 0);;
		$list = D("Home/Order")->getLists($map);
		$this->assign('list', $list);// 赋值数据集
		$page = D("Home/Order")->getPage($map);
		$this->assign('page', $page);//
		$this->meta_title = '酒店订单';
		$this->display("hotel");
	}
	
	/*****旅游订单****/
	public function line() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();
		$this->assign("custommoney", M("customer")->where("id=%d", $uid)->getField("balance"));
		//自动发货
		//$this->auto_express($uid);
		
		/* 数据分页*/
		$map['ordertype'] = 3;
		$map['uid']       = $uid;
		$map['total']     = array ('gt', 0);;
		$list = D("Home/Order")->getLists($map);
		$this->assign('list', $list);// 赋值数据集
		$page = D("Home/Order")->getPage($map);
		$this->assign('page', $page);//
		$this->meta_title = '旅游订单';
		$this->display("line");
	}
	
	//机票订单
	public function jipiao() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
			exit;
		}
		
		
		$user = M('customer')->where("openid='%s'", $this->openid)->find();
		
		$list = M('jipiao_order')->where(array ('uid' => $user['id'], "isactive" => 1))->order("addtime desc")->select();
		$this->assign('list', $list);
		$this->meta_title = '机票订单';
		$this->display("jipiao");
	}
	
	public function getkuaidi($typeCom, $typeNu) {

//		$AppKey = C('100KEY');//请将XXXXXX替换成您在http://kuaidi100.com/app/reg.html申请到的KEY
//		$url    = 'http://api.kuaidi100.com/api?id=' . $AppKey . '&com=' . $typeCom . '&nu=' . $typeNu . '&show=2&muti=1&order=asc';
		//请勿删除变量$powered 的信息，否者本站将不再为你提供快递接口服务。
		$powered = '请点击：<a href="http://m.kuaidi100.com/result.jsp?nu=' . $typeNu . '" target="_blank">查快递</a>  ';
		//优先使用curl模式发送数据
		/*
		if (function_exists('curl_init') == 1) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt($curl, CURLOPT_TIMEOUT, 5);
			$get_content = curl_exec($curl);
			curl_close($curl);
		} else {
			Vendor("Snoopy.Snoopy");
			$snoopy          = new \Vendor\Snoopy\Snoopy();
			$snoopy->referer = 'http://www.google.com/';//伪装来源
			$snoopy->fetch($url);
			$get_content = $snoopy->results;
		}
		*/
		
		return $powered;
		//print_r($get_content . '<br/>' . $powered);
	}
}
