<?php
namespace Wechat\Controller;


use Home\Model\SuiteModel;

/**
 * 体检套餐控制器
 * $url= $_SERVER[HTTP_HOST]; //获取当前域名
 */
class SuiteController extends FollowerController {

	public function _initialize() {
		parent::_initialize();
	}

	public function index() {
		$this->assign("meta_title", "健康体检");
		$suites = M("suite")->field("id,title,pic,price_sex_0,price_sex_1,buy_count")
		                    ->where("status=%d", \Admin\Controller\SuiteController::STATUS_OK)
		                    ->order("sort")->select();
		$this->assign("suites", $suites);
		$this->display();
	}

	public function detail($id) {
		$suite = M("suite")->where("status>%d",  \Admin\Controller\SuiteController::STATUS_OK)
		                   ->find($id);
		if (!$suite) {
			$this->error("访问出错!套餐不存在!");
		}
		$period = M("suite_period")->where("s_id=%d and period > NOW()", $id)->order('period')->limit(8)->select();
		$this->assign("meta_title", $suite['title']);
		$this->assign("data", $suite);
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
		$suite = M("suite")->where("status>%d", \Admin\Controller\SuiteController::STATUS_DELETED)
		                   ->find($s_id);

		if (!$suite) {
			$this->error("访问出错!套餐不存在!");
		}
		$this->assign("meta_title", $suite['title']);
		$period = M("suite_period")->where("s_id=%d and id = %d", $s_id, $pid)->find();
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
		$this->assign("meta_title", $suite['title']);
		$this->assign("data", $suite);
		$this->assign("period", $period);

		$this->display();

	}
	public funciton geti(){
		

		$api = A('Api/User');
		$api->login51URL();

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
			$suite  = M("suite")->where("status>%d", \Admin\Controller\SuiteController::STATUS_DELETED)
			                    ->find($s_id);
			$period = M("suite_period")->where("s_id=%d and id = %d", $s_id, $p_id)->find();
			if (!$period) {
				$this->error("访问出错!该时段不能预约!");
			}
			if (!$suite) {
				$this->error("访问出错!套餐不存在!");
			}
			$login_u = session("user_auth");

			$this->assign("meta_title", $suite['title']);
			$this->assign("data", $suite);
			$this->assign("period", $period);
			$this->assign("num", $num);
			$this->assign("time", $time);
			$this->assign("p_id", $p_id);
			$this->assign("s_id", $s_id);
			$this->assign("sex", $sex);
			$order_info = array ();
			$id_nums    = I('post.id_num');


			$exist = M("suite_order_info")->where(array (
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
					"source"   => \Home\Controller\SuiteController::ORDER_SOURCE_WEB,
					's_id'     => $s_id,
					'p_id'     => $p_id,
					'status'   => \Home\Controller\SuiteController::ORDER_STATUS_WAITING_PAY,
					'ctime'    => time()

				);
			}

			session("order", array (
				's_id'     => $s_id,
				'p_id'     => $p_id,
				"time"     => $time,
				'count'    => $num,
				'cost'     => $suite[ 'price_sex_' . $sex ] * $num,
				'status'   => \Home\Controller\SuiteController::ORDER_STATUS_WAITING_PAY,
				'c_id'     => $login_u['id'],
				'ctime'    => time(),
				'order_no' => build_order_no()
			));
			session("order_info", $order_info);
			$this->assign("info", $order_info);
			$this->display();
		} else if (IS_AJAX) {
			$odb     = M("suite_order");
			$order   = session('order');
			$last_id = $odb->add($order);
			if ($last_id) {
				$order_info = session("order_info");
				foreach ($order_info as &$i) {
					$i['o_id'] = $last_id;
				}
				$db_info = M("suite_order_info");
				$result  = $db_info->addAll($order_info);
				if ($result !== false) {
					M('suite')->where("id=%d", $order['s_id'])->setInc('buy_count');
					M('suite_period')->where("id=%d", $order['p_id'])->setInc('use_num', $order['count']);
					session("order", null);
					session("order_info", null);
					$this->success("预约成功!", U('Wechat/SuiteOrder/index'));
				} else {
					$this->error("出错!" . $db_info->getDbError());
				}
			}

		}

	}
}