<?php
namespace Wechat\Controller;


/**
 * 体检套餐控制器
 * $url= $_SERVER[HTTP_HOST]; //获取当前域名
 */
class ShoppingCarController extends FollowerController {

	public function _initialize() {
		parent::_initialize();
	}

	public function index() {
		$this->assign("meta_title", "购物车");
		$this->display();
	}

	public function detail($id) {
		$ShoppingCar = M("ShoppingCar")->where("status>%d", \Admin\Controller\ShoppingCarController::STATUS_DELETED)
		                               ->find($id);
		if (!$ShoppingCar) {
			$this->error("访问出错!套餐不存在!");
		}
		$period = M("ShoppingCar_period")->where("s_id=%d and period > NOW()", $id)->order('period')->limit(8)->select();
		$this->assign("meta_title", $ShoppingCar['title']);
		$this->assign("data", $ShoppingCar);
		$this->assign("period", $period);
		$this->display();
	}
	
	public function order() {
		$pid  = I("get.pid");
		$s_id = I("get.s_id");
		$num  = I("get.num");
		$time = I("get.time");
		$sex  = I("get.sex");

		if (!$pid || !$s_id || !$num || !$time) {
			$this->error("访问出错!");
		}
		$ShoppingCar = M("ShoppingCar")->where("status>%d", \Admin\Controller\ShoppingCarController::STATUS_DELETED)
		                               ->find($s_id);

		if (!$ShoppingCar) {
			$this->error("访问出错!套餐不存在!");
		}
		$this->assign("meta_title", $ShoppingCar['title']);
		$period = M("ShoppingCar_period")->where("s_id=%d and id = %d", $s_id, $pid)->find();
		if (!$period) {
			$this->error("访问出错!该时段不能预约!");
		}

		$store_num = $period['total_num'] - $period['use_num'];
		if ($store_num < $num) {
			$this->error("库存不足以预订,请修改预约人数!");
		}
		$this->assign("num", $num);
		$this->assign("sex", $sex);
		$this->assign("p_id", $pid);
		$this->assign("s_id", $s_id);
		$this->assign("time", $time);
		$this->assign("meta_title", $ShoppingCar['title']);
		$this->assign("data", $ShoppingCar);
		$this->assign("period", $period);

		$this->display();

	}

	public function order_confirm() {
		if (IS_POST) {
			$p_id = I("post.p_id");
			$s_id = I("post.s_id");
			$num  = I("post.num");
			$time = I("post.time");
			$sex  = I("post.sex");

			if (!$p_id || !$s_id || !$num || !$time) {
				$this->error("访问出错!");
			}
			$ShoppingCar = M("ShoppingCar")->where("status>%d", \Admin\Controller\ShoppingCarController::STATUS_DELETED)
			                               ->find($s_id);
			$period      = M("ShoppingCar_period")->where("s_id=%d and id = %d", $s_id, $p_id)->find();
			if (!$period) {
				$this->error("访问出错!该时段不能预约!");
			}
			if (!$ShoppingCar) {
				$this->error("访问出错!套餐不存在!");
			}
			$login_u = session("user_auth");

			$this->assign("meta_title", $ShoppingCar['title']);
			$this->assign("data", $ShoppingCar);
			$this->assign("period", $period);
			$this->assign("num", $num);
			$this->assign("time", $time);
			$this->assign("p_id", $p_id);
			$this->assign("s_id", $s_id);
			$this->assign("sex", $sex);
			$order_info = array ();
			$id_nums    = I('post.id_num');


			$exist = M("ShoppingCar_order_info")->where(array (
				                                            'id_num' => array ('IN', $id_nums),
				                                            'p_id'   => $p_id
			                                            ))->find();
			if ($exist) {
				$this->error("您预订的人中,已经有人预约过了,请检查!");
			}

			$realnames = I('post.realname');
			$tels      = I('post.tel');
			for ($i = 0; $i < $num; $i++) {
				$order_info[] = array (
					"id_num"   => $id_nums[ $i ],
					"realname" => $realnames[ $i ],
					"sex"      => $sex,
					"tel"      => $tels[ $i ],
					"source"   => \Home\Controller\ShoppingCarController::ORDER_SOURCE_WEB,
					's_id'     => $s_id,
					'p_id'     => $p_id,
					'status'   => \Home\Controller\ShoppingCarController::ORDER_STATUS_WAITING_PAY,
					'ctime'    => time()

				);
			}

			session("order", array (
				's_id'     => $s_id,
				'p_id'     => $p_id,
				"time"     => $time,
				'count'    => $num,
				'cost'     => $ShoppingCar[ 'price_sex_' . $sex ] * $num,
				'status'   => \Home\Controller\ShoppingCarController::ORDER_STATUS_WAITING_PAY,
				'c_id'     => $login_u['id'],
				'ctime'    => time(),
				'order_no' => build_order_no()
			));
			session("order_info", $order_info);
			$this->assign("info", $order_info);
			$this->display();
		} else if (IS_AJAX) {
			$odb     = M("ShoppingCar_order");
			$order   = session('order');
			$last_id = $odb->add($order);
			if ($last_id) {
				$order_info = session("order_info");
				foreach ($order_info as &$i) {
					$i['o_id'] = $last_id;
				}
				$db_info = M("ShoppingCar_order_info");
				$result  = $db_info->addAll($order_info);
				if ($result !== false) {
					M('ShoppingCar')->where("id=%d", $order['s_id'])->setInc('buy_count');
					M('ShoppingCar_period')->where("id=%d", $order['p_id'])->setInc('use_num', $order['count']);
					session("order", null);
					session("order_info", null);
					$this->success("预约成功!", U('Wechat/ShoppingCarOrder/index'));
				} else {
					$this->error("出错!" . $db_info->getDbError());
				}
			}

		}

	}
}