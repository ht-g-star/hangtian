<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | author 烟消云散 <1010422715@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Think\Log;

/**
 * 后台订单控制器
 *
 */
class OrderController extends AdminController {
	
	/**
	 * 订单管理
	 * author Todd
	 */
	public function index() {
		/* 查询条件初始化 */
		$status = $_GET['status'];
		if (isset($_GET['status'])) {
			switch ($status) {
				case '0':
					$meta_title = "所有订单";
					break;
				case '1':
					$map['status'] = $status;
					$meta_title    = "已提交订单";
					break;
				case '2':
					$map['status'] = $status;
					$meta_title    = "已发货订单";
					break;
				case '3':
					$map['status'] = $status;
					$meta_title    = "已签收订单";
					break;
				case '6':
					$map['status'] = $status;
					$meta_title    = "已关闭订单";
					break;
				
			}
		} else {
			$status     = 0;
			$meta_title = "所有订单";
		}
		if (isset($_GET['orderid'])) {
			$map['orderid'] = array ('like', '%' . (string)I('orderid') . '%');
		}
		if (isset($_GET['begin_time'])) {
			$map['update_time'][] = array ('egt', strtotime(I('begin_time')));
		}
		if (isset($_GET['end_time'])) {
			$map['update_time'][] = array ('elt', 24 * 60 * 60 + strtotime(I('end_time')));
		}
		if (isset($_GET['customer_name'])) {
			$_cid = M('customer')->where(array ('realname' => array ('like', '%' . (string)I('customer_name') . '%')))->getField('id', true);
			if ($_cid) {
				$map['uid'] = array ("IN", $_cid);
			} else {
				$map['uid'] = array ("IN", array (0));
			}
		}
		
		if (isset($_GET['receiver_name'])) {
			$__cids = M('address')->where(array ('realname' => array ('like', '%' . (string)I('receiver_name') . '%')))->getField('id', true);
			if ($__cids) {
				$map['addressid'] = array ("IN", $__cids);
			} else {
				$map['addressid'] = array ("IN", array (-1));
			}
		}
		if ($status == 0) {
			$map['status'] = array ("gt", $status);
		} else {
			$map['status'] = $status;
		}
		$map['ordertype'] = 1;
		$map['ispay']     = 1;
		$total            = M('order')->where($map)->count();
		$listRows         = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
		$REQUEST          = (array)I('request.');
		
		$page = new \Think\Page($total, $listRows, $REQUEST);
		if ($total > $listRows) {
			$page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		}
		$p = $page->show();
		$this->assign('_page', $p ? $p : '');
		$this->assign('_total', $total);
		$list   = M("order")->where($map)->limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
		$detail = M("shoplist");
		foreach ($list as &$row) {
			$customer = M("customer")->find($row['uid']);
			$address  = M("address")->find($row['addressid']);
			$pay      = M("pay")->where("(out_trade_no = '%s' or out_trade_no='%s') and out_trade_no!='' ", $row['tag'], $row['orderid'])->find();
			
			$row['realname']      = $customer['realname'];
			$row['id_num']        = $customer['id_num'];
			$row['card_num']      = $customer['card_num'];
			$row['mobile']        = $customer['mobile'];
			$row['receiver_name'] = $address['realname'];
			$row['receiver_tel']  = $address['cellphone'];
			$row['pay_time']      = $pay['update_time'];
			
			if ($pay['status'] != 0) {
				$row['pay_type'] = '';
				$_pay_online     = $pay['param'] ? 1 : 0;
				if ($_pay_online && $pay['callback']) {
					$row['pay_type'] .= '支付宝';
//				$row['pay_param'] = $pay['param'];
				} else if ($_pay_online && !$pay['callback']) {
					$row['pay_type'] .= '微信';
//				$row['pay_param'] = $pay['param'];
				} else {
					$row['pay_type'] .= '余额';
				}
				
				if ($pay['status'] == -1) {
					$row['pay_type'] .= '已退';
				}
			} else {
				
				$row['pay_type'] = '未支付';
			}
			
			
			$row['goods'] = $detail->where("orderid='%s'", $row['id'])->select();
			if (empty($row['goods'])) {
				//订单ID丢了?
				$row['goods'] = $detail->where("tag='%s'", $row['tag'])->select();
			}
			$row['goods_count'] = count($row['goods']);
			
			
			//
		}
		
		foreach ($list as $key => $value) {
			$pay_time[ $key ]    = $value['pay_time'];
			$create_time[ $key ] = $value['create_time'];
		}
		array_multisort($list, SORT_DESC, $pay_time, SORT_DESC, $create_time);
		
		$this->assign('status', $status);
		$this->meta_title = $meta_title;
		$this->assign('list', $list);
		
		// 记录当前列表页的cookie
		Cookie('__forward__', $_SERVER['REQUEST_URI']);
		
		$this->display();
	}
	
	/**
	 * 酒店订单管理
	 */
	public function jdindex() {
		/* 查询条件初始化 */
		$status = $_GET['status'];
		if (isset($_GET['status'])) {
			switch ($status) {
				case '0':
					$meta_title = "所有订单";
					break;
				case '1':
					$map['status'] = $status;
					$meta_title    = "已提交订单";
					break;
				case '2':
					$map['status'] = $status;
					$meta_title    = "已发货订单";
					break;
				case '3':
					$map['status'] = $status;
					$meta_title    = "已签收订单";
					break;
				case '6':
					$map['status'] = $status;
					$meta_title    = "已关闭订单";
					break;
				
			}
		} else {
			$status     = 0;
			$meta_title = "所有订单";
		}
		
		
		if (isset($_GET['orderid'])) {
			$map['orderid'] = array ('like', '%' . (string)I('orderid') . '%');
		}
		if (isset($_GET['begin_time'])) {
			$map['update_time'][] = array ('egt', strtotime(I('begin_time')));
		}
		if (isset($_GET['end_time'])) {
			$map['update_time'][] = array ('elt', 24 * 60 * 60 + strtotime(I('end_time')));
		}
		if (isset($_GET['customer_name'])) {
			$map['uid'] = array ("IN", M('customer')->where(array ('realname' => array ('like', '%' . (string)I('customer_name') . '%')))->getField('id', true));
		}
		
		if (isset($_GET['receiver_name'])) {
			$map['addressid'] = array ("IN", M('address')->where(array ('realname' => array ('like', '%' . (string)I('receiver_name') . '%')))->getField('id', true));
		}
		if ($status == 0) {
			$map['status'] = array ("gt", -2);
		} else {
			$map['status'] = $status;
		}
		//酒店订单
		$map['ordertype'] = 2;
		$total            = M('order')->where($map)->count();
		$listRows         = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
		$REQUEST          = (array)I('request.');
		
		$page = new \Think\Page($total, $listRows, $REQUEST);
		if ($total > $listRows) {
			$page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		}
		$p = $page->show();
		$this->assign('_page', $p ? $p : '');
		$this->assign('_total', $total);
		$list   = M("order")->where($map)->limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
		$detail = M("shoplist");
		foreach ($list as &$row) {
			$customer = M("customer")->find($row['uid']);
			$address  = M("address")->find($row['addressid']);
			$pay      = M("pay")->where("(out_trade_no = '%s' or out_trade_no='%s') and out_trade_no!='' ", $row['tag'], $row['orderid'])->find();
			
			$row['realname']      = $customer['realname'];
			$row['id_num']        = $customer['id_num'];
			$row['card_num']      = $customer['card_num'];
			$row['mobile']        = $customer['mobile'];
			$row['receiver_name'] = $address['realname'];
			$row['receiver_tel']  = $address['cellphone'];
			$row['pay_time']      = $pay['update_time'];
			
			if ($pay['status'] != 0) {
				$row['pay_type'] = '';
				$_pay_online     = $pay['param'] ? 1 : 0;
				if ($_pay_online && $pay['callback']) {
					$row['pay_type'] .= '支付宝';
//				$row['pay_param'] = $pay['param'];
				} else if ($_pay_online && !$pay['callback']) {
					$row['pay_type'] .= '微信';
//				$row['pay_param'] = $pay['param'];
				} else {
					$row['pay_type'] .= '余额';
				}
				
				if ($pay['status'] == -1) {
					$row['pay_type'] .= '已退';
				}
			} else {
				$row['pay_type'] = '未支付';
			}
			
			
			$row['goods'] = $detail->where("orderid='%s'", $row['id'])->select();
			if (empty($row['goods'])) {
				//订单ID丢了?
				$row['goods'] = $detail->where("tag='%s'", $row['tag'])->select();
			}
			$row['goods_count'] = count($row['goods']);
			
			
			//
		}
		
		foreach ($list as $key => $value) {
			$pay_time[ $key ]    = $value['pay_time'];
			$create_time[ $key ] = $value['create_time'];
		}
		array_multisort($list, SORT_DESC, $pay_time, SORT_DESC, $create_time);
		
		$this->assign('status', $status);
		$this->meta_title = $meta_title;
		$this->assign('list', $list);
		
		// 记录当前列表页的cookie
		Cookie('__forward__', $_SERVER['REQUEST_URI']);
		$this->display('jdindex');
	}
	
	/**
	 * 线路订单
	 */
	public function xlindex() {
		/* 查询条件初始化 */
		$status = $_GET['status'];
		if (isset($_GET['status'])) {
			switch ($status) {
				case '0':
					$meta_title = "所有订单";
					break;
				case '1':
					$map['status'] = $status;
					$meta_title    = "已提交订单";
					break;
				case '2':
					$map['status'] = $status;
					$meta_title    = "已发货订单";
					break;
				case '3':
					$map['status'] = $status;
					$meta_title    = "已签收订单";
					break;
				case '6':
					$map['status'] = $status;
					$meta_title    = "已关闭订单";
					break;
				
			}
		} else {
			$status     = 0;
			$meta_title = "所有订单";
		}
		if (isset($_GET['orderid'])) {
			$map['orderid'] = array ('like', '%' . (string)I('orderid') . '%');
		}
		if (isset($_GET['begin_time'])) {
			$map['update_time'][] = array ('egt', strtotime(I('begin_time')));
		}
		if (isset($_GET['end_time'])) {
			$map['update_time'][] = array ('elt', 24 * 60 * 60 + strtotime(I('end_time')));
		}
		if (isset($_GET['customer_name'])) {
			$map['uid'] = array ("IN", M('customer')->where(array ('realname' => array ('like', '%' . (string)I('customer_name') . '%')))->getField('id', true));
		}
		
		if (isset($_GET['receiver_name'])) {
			$map['addressid'] = array ("IN", M('address')->where(array ('realname' => array ('like', '%' . (string)I('receiver_name') . '%')))->getField('id', true));
		}
		if ($status == 0) {
			$map['status'] = array ("gt", $status);
		} else {
			$map['status'] = $status;
		}
		//线路订单
		$map['ordertype'] = 3;
		$total            = M('order')->where($map)->count();
		$listRows         = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
		$REQUEST          = (array)I('request.');
		
		$page = new \Think\Page($total, $listRows, $REQUEST);
		if ($total > $listRows) {
			$page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		}
		$p = $page->show();
		$this->assign('_page', $p ? $p : '');
		$this->assign('_total', $total);
		$list   = M("order")->where($map)->limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
		$detail = M("shoplist");
		foreach ($list as &$row) {
			$customer = M("customer")->find($row['uid']);
			$address  = M("address")->find($row['addressid']);
			$pay      = M("pay")->where("(out_trade_no = '%s' or out_trade_no='%s') and out_trade_no!='' ", $row['tag'], $row['orderid'])->find();
			
			$row['realname']      = $customer['realname'];
			$row['id_num']        = $customer['id_num'];
			$row['card_num']      = $customer['card_num'];
			$row['mobile']        = $customer['mobile'];
			$row['receiver_name'] = $address['realname'];
			$row['receiver_tel']  = $address['cellphone'];
			$row['pay_time']      = $pay['update_time'];
			
			if ($pay['status'] != 0) {
				$row['pay_type'] = '';
				$_pay_online     = $pay['param'] ? 1 : 0;
				if ($_pay_online && $pay['callback']) {
					$row['pay_type'] .= '支付宝';
//				$row['pay_param'] = $pay['param'];
				} else if ($_pay_online && !$pay['callback']) {
					$row['pay_type'] .= '微信';
//				$row['pay_param'] = $pay['param'];
				} else {
					$row['pay_type'] .= '余额';
				}
				
				if ($pay['status'] == -1) {
					$row['pay_type'] .= '已退';
				}
			} else {
				$row['pay_type'] = '未支付';
			}
			
			
			$row['goods'] = $detail->where("orderid='%s'", $row['id'])->select();
			if (empty($row['goods'])) {
				//订单ID丢了?
				$row['goods'] = $detail->where("tag='%s'", $row['tag'])->select();
			}
			$row['goods_count'] = count($row['goods']);
			
			
			//
		}
		
		foreach ($list as $key => $value) {
			$pay_time[ $key ]    = $value['pay_time'];
			$create_time[ $key ] = $value['create_time'];
		}
		array_multisort($list, SORT_DESC, $pay_time, SORT_DESC, $create_time);
		
		$this->assign('status', $status);
		$this->meta_title = $meta_title;
		$this->assign('list', $list);
		
		// 记录当前列表页的cookie
		Cookie('__forward__', $_SERVER['REQUEST_URI']);
		
		$this->display();
	}

//导出excel
	public function out() {
		$status = $_GET['status'];
		if (isset($_GET['status'])) {
			switch ($status) {
				case '0':
					$meta_title = "所有订单";
					break;
				case '1':
					$map['status'] = $status;
					$meta_title    = "已提交订单";
					break;
				case '2':
					$map['status'] = $status;
					$meta_title    = "已发货订单";
					break;
				case '3':
					$map['status'] = $status;
					$meta_title    = "已签收订单";
					break;
				
			}
		} else {
			$status = 0;
		}
		if (isset($_GET['orderid'])) {
			$map['orderid'] = array ('like', '%' . (string)I('orderid') . '%');
		}
		if (isset($_GET['begin_time'])) {
			$map['update_time'][] = array ('egt', strtotime(I('begin_time')));
		}
		if (isset($_GET['end_time'])) {
			$map['update_time'][] = array ('elt', 24 * 60 * 60 + strtotime(I('end_time')));
		}
		if (isset($_GET['customer_name'])) {
			$map['uid'] = array ("IN", M('customer')->where(array ('realname' => array ('like', '%' . (string)I('customer_name') . '%')))->getField('id', true));
		}
		
		if (isset($_GET['receiver_name'])) {
			$map['addressid'] = array ("IN", M('address')->where(array ('realname' => array ('like', '%' . (string)I('receiver_name') . '%')))->getField('id', true));
		}
		
		if (isset($_GET['id'])) {
			$map['id'] = array ("IN", $_GET['id']);
		}
		
		if ($status == 0) {
			$map['status'] = array ("gt", $status);
		} else {
			$map['status'] = $status;
		}
		$map['ordertype'] = I('ordertype') ? I('ordertype') : 1;
		$total            = M('order')->where($map)->count();
		$this->assign('_total', $total);
		$list       = M("order")->where($map)->limit(1000)->order("id desc")->select();
		$detail     = M("shoplist");
		$rows_count = 0;
		foreach ($list as &$row) {
			$customer = M("customer")->find($row['uid']);
			$address  = M("address")->find($row['addressid']);
			$pay      = M("pay")->where("(out_trade_no = '%s' or out_trade_no='%s') and out_trade_no!='' ", $row['tag'], $row['orderid'])->find();
			
			
			$row['realname']         = $customer['realname'];
			$row['id_num']           = $customer['id_num'];
			$row['card_num']         = $customer['card_num'];
			$row['mobile']           = $customer['mobile'];
			$row['receiver_name']    = $address['realname'];
			$row['receiver_tel']     = $address['cellphone'];
			$row['receiver_address'] = $address['province'] . $address['city'] . $address['area'] . $address['address'];
			$row['create_time']      = date("Y-m-d H:i:s", $row['create_time']);
			$row['pay_time']         = date("Y-m-d H:i:s", $pay['update_time']);
			if ($pay['status'] != 0) {
				$row['pay_type'] = '';
				$_pay_online     = $pay['param'] ? 1 : 0;
				if ($_pay_online && $pay['callback']) {
					$row['pay_type'] .= '支付宝';
//				$row['pay_param'] = $pay['param'];
				} else if ($_pay_online && !$pay['callback']) {
					$row['pay_type'] .= '微信';
//				$row['pay_param'] = $pay['param'];
				} else {
					$row['pay_type'] .= '余额';
				}
				
				if ($pay['status'] == -1) {
					$row['pay_type'] .= '已退';
				}
			} else {
				$row['pay_type'] = '未支付';
			}
			
			$row['goods'] = $detail->where("orderid='%s'", $row['id'])->select();
			if (empty($row['goods'])) {
				//订单ID丢了?
				$row['goods'] = $detail->where("tag='%s'", $row['tag'])->select();
			}
			$row['goods_count'] = count($row['goods']);
			$rows_count += $row['goods_count'];
			$_goods     = $detail->where("orderid='%s'", $row['id'])->getField("goodid,num,parameters,price", true);
			$goods_info = array ();
			foreach ($_goods as $g) {
				$goods_name   = get_good_name($g['goodid']);
				$g['title']   = $goods_name;
				$g['total']   = floatval($g['price']) * intval($g['num']);
				$goods_info[] = $g;
			}
			$row['goods']       = $goods_info;
			$row['goods_count'] = count($row['goods']);
			
			//订单状态
			switch ($row['status']) {
				case -1:
					$row['status'] = '未付款';
					break;
				case 1:
					$row['status'] = '已提交';
					break;
				case 2:
					$row['status'] = '已发货';
					break;
				case 3:
					$row['status'] = '已签收';
					break;
				case 4:
					$row['status'] = '取消订单';
					break;
				case 5:
					$row['status'] = '取消订单拒绝';
					break;
				case 6:
					$row['status'] = '已关闭';
					break;
				default:
					$row['status'] = '订单错误';
					break;
			}
		}
		foreach ($list as $key => $value) {
			$pay_time[ $key ]    = $value['pay_time'];
			$create_time[ $key ] = $value['create_time'];
		}
		array_multisort($list, SORT_DESC, $pay_time, SORT_DESC, $create_time);
		
		$xlsCell = array (
			array ('card_num', '会员号'),
			array ('realname', '会员姓名'),
			array ('mobile', '会员手机'),
			array ('orderid', '订单号'),
			
			array ('goods.goodid', '商品编号'),
			array ('goods.title', '商品名称'),
			array ('goods.num', '商品数量'),
			array ('goods.price', '商品单价'),
			array ('goods.total', '合计'),
			
			array ('total_money', '总价'),
			array ('status', '订单状态'),
			array ('receiver_name', '收货人'),
			array ('receiver_tel', '收货电话'),
			array ('receiver_address', '收货地址'),
			array ('message', '备注'),
			//			array ('id_num', '身份证号'),
			//			array ('tag', '支付订单号'),
			array ('create_time', '订单时间'),
			array ('pay_time', '更新时间'),
			array ('pay_type', '支付方式'),
			
			//			array ('ship_price', '运费'),
			//			array ('score', '消耗积分'),
			//			array ('coupon_money', '消耗优惠券金额'),
			//			array ('uid', '用户uid')
		
		);
		if (!$list) {
			$this->error('无数据');
		}
		$ordertype = I('ordertype') ? I('ordertype') : 1;
		$file      = '导出订单';
		switch ($ordertype) {
			case '1':
				$file = '导出订单';
				break;
			case '2':
				$file = '导出酒店订单';
				break;
			case '3':
				$file = '导出线路订单';
				break;
		}
		$this->exportExcel($file . date("YmdHis"), $xlsCell, $list, $rows_count);
	}
	
	//导出酒店线路
	public function outHotelLine() {
		$status = $_GET['status'];
		if (isset($_GET['status'])) {
			switch ($status) {
				case '0':
					$meta_title = "所有订单";
					break;
				case '1':
					$map['status'] = $status;
					$meta_title    = "已提交订单";
					break;
				case '2':
					$map['status'] = $status;
					$meta_title    = "已发货订单";
					break;
				case '3':
					$map['status'] = $status;
					$meta_title    = "已签收订单";
					break;
				
			}
		} else {
			$status = 0;
		}
		if (isset($_GET['orderid'])) {
			$map['orderid'] = array ('like', '%' . (string)I('orderid') . '%');
		}
		if (isset($_GET['begin_time'])) {
			$map['update_time'][] = array ('egt', strtotime(I('begin_time')));
		}
		if (isset($_GET['end_time'])) {
			$map['update_time'][] = array ('elt', 24 * 60 * 60 + strtotime(I('end_time')));
		}
		if (isset($_GET['customer_name'])) {
			$map['uid'] = array ("IN", M('customer')->where(array ('realname' => array ('like', '%' . (string)I('customer_name') . '%')))->getField('id', true));
		}
		
		if (isset($_GET['receiver_name'])) {
			$map['addressid'] = array ("IN", M('address')->where(array ('realname' => array ('like', '%' . (string)I('receiver_name') . '%')))->getField('id', true));
		}
		if ($status == 0) {
			$map['status'] = array ("gt", $status);
		} else {
			$map['status'] = $status;
		}
		$map['ordertype'] = I('ordertype') ? I('ordertype') : 1;
		$total            = M('order')->where($map)->count();
		$this->assign('_total', $total);
		$list       = M("order")->where($map)->limit(1000)->select();
		$detail     = M("shoplist");
		$rows_count = 0;
		foreach ($list as &$row) {
			$customer = M("customer")->find($row['uid']);
			$address  = M("address")->find($row['addressid']);
			$pay      = M("pay")->where("(out_trade_no = '%s' or out_trade_no='%s') and out_trade_no!='' ", $row['tag'], $row['orderid'])->find();
			
			
			$row['realname']         = $customer['realname'];
			$row['id_num']           = $customer['id_num'];
			$row['card_num']         = $customer['card_num'];
			$row['mobile']           = $customer['mobile'];
			$row['receiver_name']    = $address['realname'];
			$row['receiver_tel']     = $address['cellphone'];
			$row['receiver_address'] = $address['province'] . $address['city'] . $address['area'] . $address['address'];
			$row['create_time']      = date("Y-m-d H:i:s", $row['create_time']);
			$row['pay_time']         = date("Y-m-d H:i:s", $pay['update_time']);
			Log::write(print_r($row, true));
			if ($pay['status'] != 0) {
				$row['pay_type'] = '';
				$_pay_online     = $pay['param'] ? 1 : 0;
				if ($_pay_online && $pay['callback']) {
					$row['pay_type'] .= '支付宝';
//				$row['pay_param'] = $pay['param'];
				} else if ($_pay_online && !$pay['callback']) {
					$row['pay_type'] .= '微信';
//				$row['pay_param'] = $pay['param'];
				} else {
					$row['pay_type'] .= '余额';
				}
				
				if ($pay['status'] == -1) {
					$row['pay_type'] .= '已退';
				}
			} else {
				$row['pay_type'] = '未支付';
			}
			
			$row['goods'] = $detail->where("orderid='%s'", $row['id'])->select();
			if (empty($row['goods'])) {
				//订单ID丢了?
				$row['goods'] = $detail->where("tag='%s'", $row['tag'])->select();
			}
			$row['goods_count'] = count($row['goods']);
			$rows_count += $row['goods_count'];
			$_goods     = $detail->where("orderid='%s'", $row['id'])->getField("goodid,num,parameters,price", true);
			$goods_info = array ();
			foreach ($_goods as $g) {
				$goods_name   = get_good_name($g['goodid']);
				$g['title']   = $goods_name;
				$g['total']   = floatval($g['price']) * intval($g['num']);
				$goods_info[] = $g;
			}
			$row['goods']       = $goods_info;
			$row['goods_count'] = count($row['goods']);
			
			//订单状态
			switch ($row['status']) {
				case -1:
					$row['status'] = '未付款';
					break;
				case 1:
					$row['status'] = '已提交';
					break;
				case 2:
					$row['status'] = '已发货';
					break;
				case 3:
					$row['status'] = '已签收';
					break;
				case 4:
					$row['status'] = '取消订单';
					break;
				case 5:
					$row['status'] = '取消订单拒绝';
					break;
				case 6:
					$row['status'] = '已关闭';
					break;
				default:
					$row['status'] = '订单错误';
					break;
			}
		}
		foreach ($list as $key => $value) {
			$pay_time[ $key ]    = $value['pay_time'];
			$create_time[ $key ] = $value['create_time'];
		}
		array_multisort($list, SORT_DESC, $pay_time, SORT_DESC, $create_time);
		
		if (!$list) {
			$this->error('无数据');
		}
		$ordertype = I('ordertype') ? I('ordertype') : 1;
		$file      = '导出订单';
		switch ($ordertype) {
			case '1':
				$file = '导出订单';
				break;
			case '2':
				$file    = '导出酒店订单';
				$xlsCell = array (
					array ('card_num', '会员号'),
					array ('realname', '会员姓名'),
					array ('mobile', '会员手机'),
					array ('orderid', '订单号'),
					
					array ('goods.goodid', '商品编号'),
					array ('goodsname', '商品名称'),
					array ('goods.num', '商品数量'),
					array ('goods.price', '商品单价'),
					array ('goods.total', '合计'),
					
					array ('total_money', '总价'),
					// array ('status', '订单状态'),
					array ("cftime", "入住时间"),
					array ('receiver_name', '入住人'),
					array ('crj_num', '入住人数'),
					array ('receiver_tel', '联系电话'),
					
					
					// array ('id_num', '身份证号'),
					//			array ('tag', '支付订单号'),
					array ('create_time', '订单时间'),
					array ('pay_time', '更新时间'),
					array ('pay_type', '支付方式'),
					
					//			array ('ship_price', '运费'),
					//			array ('score', '消耗积分'),
					//			array ('coupon_money', '消耗优惠券金额'),
					//			array ('uid', '用户uid')
				);
				break;
			case '3':
				$file    = '导出线路订单';
				$xlsCell = array (
					array ('card_num', '会员号'),
					array ('realname', '会员姓名'),
					array ('mobile', '会员手机'),
					array ('orderid', '订单号'),
					
					array ('goods.goodid', '商品编号'),
					array ('goodsname', '商品名称'),
					array ('goods.num', '商品数量'),
					array ('goods.price', '商品单价'),
					array ('goods.total', '合计'),
					
					array ('total_money', '总价'),
					// array ('status', '订单状态'),
					array ("cftime", "出行日期"),
					array ('receiver_name', '出行人'),
					array ('receiver_tel', '出行人电话'),
					
					array ('crj_num', '出行成人数'),
					array ('rtj_num', '出行儿童数'),
					
					// array ('id_num', '身份证号'),
					//			array ('tag', '支付订单号'),
					array ('create_time', '订单时间'),
					array ('pay_time', '更新时间'),
					array ('pay_type', '支付方式'),
					
					//			array ('ship_price', '运费'),
					//			array ('score', '消耗积分'),
					//			array ('coupon_money', '消耗优惠券金额'),
					//			array ('uid', '用户uid')
				);
				break;
		}
		$this->exportExcel($file . date("YmdHis"), $xlsCell, $list, $rows_count);
	}
	
	
	/**
	 * 新增订单
	 *
	 */
	public
	function add() {
		if (IS_POST) {
			$order = D('order');
			
			if (false !== $order->update()) {
				
				$this->success('新增成功', U('index'));
			} else {
				$this->error('新增失败');
			}
			
		} else {
			$this->meta_title = '新增配置';
			$this->assign('info', null);
			$this->display();
		}
	}
	
	/**
	 * 编辑订单
	 *
	 */
	public
	function edit() {
		$id = I('id');
		if (IS_POST) {
			$order = D('order');
			
			if (false !== $order->update()) {
				
				$this->success('更新成功', Cookie('__forward__'));
			} else {
				$this->error('更新失败55' . $id);
			}
			
		} else {
			/* 获取数据 */
			$info   = M('order')->find($id);
			$detail = M('order')->where("id='$id'")->select();
			foreach ($detail as $k => $v) {
				
				$tmp = explode("-", $v['cftime']);
				if (count($tmp) > 1) {
					$detail[ $k ]['rztime'] = $tmp[0] . '年' . $tmp[1] . '月' . $tmp[2] . "日";
				} else {
					$year                   = substr($v['cftime'], 0, 4);
					$month                  = substr($v['cftime'], 4, 2);
					$day                    = substr($v['cftime'], 6, 2);
					$detail[ $k ]['rztime'] = $year . '年' . $month . '月' . $day . "日";
				}
			}
			$list      = M('shoplist')->where("orderid='$id'")->select();
			$addressid = M('order')->where("id='$id'")->getField("addressid");
			
			$trans  = M("address")->where("id='$addressid'")->select();
			$cancel = M("cancel")->where("orderid='{$detail[0]['orderid']}'")->find();
			$this->assign('cancel', $cancel);
			$this->assign('trans', $trans[0]);
			if (false === $info) {
				$this->error('获取订单信息错误');
			}
			
			if (empty($info["crj_num"])) $info["crj_num"] = 0;
			if (empty($info["rtj_num"])) $info["rtj_num"] = 0;
			
			$this->assign('list', $list);
			$this->assign('detail', $detail[0]);
			$this->assign('info', $info);
			$this->meta_title = '编辑订单';
			$this->display();
		}
	}
	
	/**
	 * 订单发货
	 *
	 */
	public
	function send($id = 0) {
		
		if (IS_POST) {
			$order = D('order');
			
			if (false !== $order->update()) {
				//记录行为
				action_log('update_order', 'order', $data['id'], UID);
				$this->success('更新成功', Cookie('__forward__'));
			} else {
				$this->error('更新失败' . $id);
			}
			
		} else {
			$info = array ();
			/* 获取数据 */
			$info      = M('order')->find($id);
			$detail    = M('order')->where("id='$id'")->select();
			$list      = M('shoplist')->where("orderid='$id'")->select();
			$addressid = M('order')->where("id='$id'")->getField("addressid");
			$trans     = M("address")->where("id='$addressid'")->select();
			$this->assign('trans', $trans);
			$this->assign('list', $list);
			$this->assign('detail', $detail);
			$this->assign('info', $info);
			
			$this->meta_title = '订单发货';
			$this->display();
		}
	}
	
	public
	function complete($id = 0) {
		if (IS_POST) {
			$order = D('order');
			if (false !== $order->update()) {
				//记录行为
				action_log('update_order', 'order', $id, UID);
				$this->success('更新成功', Cookie('__forward__'));
			} else {
				$this->error('更新失败' . $id);
			}
			
		} else {
			$info = array ();
			/* 获取数据 */
			$info      = M('order')->find($id);
			$detail    = M('order')->where("id='$id'")->select();
			$list      = M('shoplist')->where("orderid='$id'")->select();
			$addressid = M('order')->where("id='$id'")->getField("addressid");
			
			$trans = M("address")->where("id='$addressid'")->select();
			$this->assign('trans', $trans);
			if (false === $info) {
				$this->error('获取订单信息错误');
			}
			$this->assign('list', $list);
			$this->assign('detail', $detail);
			$this->assign('info', $info);
			
			$this->meta_title = '订单发货';
			$this->display();
		}
	}
	
	
	/**
	 * 删除订单
	 *
	 */
	public
	function del() {
		if (IS_POST) {
			$ids   = I('post.id');
			$order = M("order");
			
			if (is_array($ids)) {
				foreach ($ids as $id) {
					
					$order->where("id='$id'")->delete();
					$shop = M("shoplist");
					$shop->where("orderid='$id'")->delete();
				}
			}
			$this->success("删除成功！");
		} else {
			$id     = I('get.id');
			$db     = M("order");
			$status = $db->where("id='$id'")->delete();
			if ($status) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}
	
	
}