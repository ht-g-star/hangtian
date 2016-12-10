<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | author 烟消云散 <1010422715@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

/**
 * 后台订单控制器
 *
 */
class CancelController extends AdminController {

	/**
	 * 订单管理
	 * author 烟消云散 <1010422715@qq.com>
	 */
	public function index() {
		/* 查询条件初始化 */

		$status = $_GET['status'];
		if (isset($_GET['status'])) {
			switch ($status) {
				case '1':
					$map['status'] = $status;
					$meta_title    = "已提交取消订单";
					break;
				case '2':
					$map['status'] = $status;
					$meta_title    = "已同意取消订单";
					break;
				

				case '3':
					$map['status'] = $status;
					$meta_title    = "已拒绝取消订单";

					break;

			}
		} else {
			$status     = 1;
			$map        = array ('status' => 1);
			$meta_title = "已提交取消订单";
		}
		if (isset($_GET['title'])) {
			$map['title'] = array ('like', '%' . (string)I('title') . '%');
		}
		if (isset($_GET['time-start'])) {
			$map['update_time'][] = array ('egt', strtotime(I('time-start')));
		}
		if (isset($_GET['time-end'])) {
			$map['update_time'][] = array ('elt', 24 * 60 * 60 + strtotime(I('time-end')));
		}

		$this->meta_title = $meta_title;
		$this->assign('status', $status);


//		$list = $this->lists('cancel', $map, 'id');
		$total    = M('cancel')->where($map)->count();
		$listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
		$REQUEST  = (array)I('request.');

		$page = new \Think\Page($total, $listRows, $REQUEST);
		if ($total > $listRows) {
			$page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		}
		$p = $page->show();
		$this->assign('_page', $p ? $p : '');
		$this->assign('_total', $total);

		$list   = M("cancel")->where($map)->limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
		$detail = M("shoplist");
		foreach ($list as &$row) {
			$order    = M("order")->where("orderid='%s'", $row['orderid'])->find();
			$customer = M("customer")->find($order['uid']);
			$address  = M("address")->find($order['addressid']);
			$pay      = M("pay")->where("(out_trade_no = '%s' or out_trade_no='%s') and out_trade_no!='' ", $order['tag'], $order['orderid'])->find();

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


			$row['goods'] = $detail->where("orderid='%s'", $row['orderid'])->select();
			if (empty($row['goods'])) {
				//订单ID丢了?
				$row['goods'] = $detail->where("tag='%s'", $order['tag'])->select();
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
		$this->assign('list', $list);
		// 记录当前列表页的cookie
		Cookie('__forward__', $_SERVER['REQUEST_URI']);

		$this->display();
	}

	/**
	 * 新增订单
	 *
	 */
	public function add() {
		if (IS_POST) {
			$Cancel = D('Cancel');
			if (false !== $Cancel->update()) {

				$this->success('新增成功', U('index'));
			} else {
				$this->error('新增失败');
			}

		} else {
			$this->meta_title = '新增订单';
			$this->assign('info', null);
			$this->display();
		}
	}

	/**
	 * 编辑订单
	 *
	 */
	public function edit($id = 0) {
		if (IS_POST) {
			$Cancel = D('Cancel');
			if (false !== $Cancel->update()) {
				//记录行为
				$this->success('更新成功', Cookie('__forward__'));
			} else {
				$this->error('更新失败' . $id);
			}

		} else {
			$info = array ();
			/* 获取数据 */
			$info    = M('cancel')->find($id);
			$orderid = $info["orderid"];
			$list    = M('order')->where("orderid='$orderid'")->select();
			$detail  = M('shoplist');
			foreach ($list as $n => $val) {
				$list[ $n ]['id'] = $detail->where('orderid=\'' . $val['id'] . '\'')->select();
			}
			$trans = M('address');
			foreach ($list as $k => $va) {
				$list[ $k ]['addressid'] = $trans->where('id=\'' . $va['addressid'] . '\'')->select();
			}
			if (false === $info) {
				$this->error('获取订单信息错误');
			}
			$this->assign('list', $list);

			$this->assign('info', $info);
			$this->meta_title = '编辑订单';
			$this->display();
		}
	}

	/**
	 * 同意取消订单
	 *
	 */
	public function agree() {
		if (IS_POST) {
			$Cancel = D('Cancel');
			if (false !== $Cancel->update()) {
				$order_id = M("order")->where("orderid='" . I('post.orderid') . "'")->getField('id');
//				back_money($order_id);
				addUserLog('同意订单取消,订单ID' . $order_id, is_login());
				$this->success('操作成功，订单已取消', U('index'));
			} else {
				$this->error('取消失败');
			}
		} else {
			$info = array ();
			/* 获取数据 */
			$id   = I('get.id');
			$info = M('cancel')->find($id);
			if (false === $info) {
				$this->error('获取订单信息错误');
			}
			$this->assign('info', $info);
			$this->meta_title = '同意取消订单';
			$this->display();
		}


	}

	public function refuse() {
		if (IS_POST) {
			$Cancel = D('Cancel');

			if (false !== $Cancel->update()) {
				addUserLog('拒绝订单取消,订单', is_login());
				$this->success('操作成功', U('index'));
			} else {
				$this->error('取消失败');
			}
		} else {
			$this->meta_title = '拒绝取消订单';
			$info             = array ();
			/* 获取数据 */
			$id   = I('get.id');
			$info = M('cancel')->find($id);
			if (false === $info) {
				$this->error('获取订单信息错误');
			}
			$this->assign('info', $info);
			$this->display();
		}

	}

	/**
	 * 删除订单
	 *
	 */
	public function del() {
		if (IS_POST) {
			$ids   = I('post.id');
			$order = M("cancel");
			
			if (is_array($ids)) {
				foreach ($ids as $id) {

					$order->where("id='$id'")->delete();

				}
			}
			$this->success("删除成功！");
		} else {
			$id     = I('get.id');
			$db     = M("cancel");
			$status = $db->where("id='$id'")->delete();
			if ($status) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}


}