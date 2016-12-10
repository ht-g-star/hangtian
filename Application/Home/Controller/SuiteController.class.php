<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
namespace Home\Controller;

use Home\Model\SuiteModel;
use OT\DataDictionary;
use User\Api\UserApi;

/**
 * 体检套餐控制器
 * $url= $_SERVER[HTTP_HOST]; //获取当前域名
 */
class SuiteController extends HomeController {

	const ORDER_SOURCE_WEB   = 0;
	const ORDER_SOURCE_ADMIN = 1;
	const ORDER_SOURCE_OTHER = 2;


	const ORDER_STATUS_MARK_REG    = 11;
	const ORDER_STATUS_OK          = 10;
	const ORDER_STATUS_FINISH      = 20;
	const ORDER_STATUS_CANCEL      = 0;
	const ORDER_STATUS_WAITING_PAY = 1;

	public function _initialize() {
		//取消强制登录
		//$this->login();
		parent::_initialize();
	}

	public function index() {
		$this->assign("meta_title", "健康体检");
		$suites = M("suite")->field("id,title,pic,price_sex_0,price_sex_1")
		                    ->where("status>%d", \Admin\Controller\SuiteController::STATUS_DISABLED)
		                    ->order("sort")->select();
		$this->assign("suites", $suites);
		$this->display();
	}
	
	public function detail($id) {

		$suite = M("suite")->where("status>%d", \Admin\Controller\SuiteController::STATUS_DELETED)
		                   ->find($id);
		if (!$suite) {
			$this->error("访问出错!套餐不存在!");
		}
		$period = M("suite_period")->where("s_id=%d and period > NOW()", $id)->order('period')->limit(8)->select();
		$this->assign("meta_title", $suite['title']);
		$this->assign("data", $suite);
		$this->assign("period", $period);
		$this->get_records($id);
		$this->get_comment($id);

		M("suite")->where("id=%d", $id)->setInc("view_count", 1, 60);
		$this->display();
	}
	
	public function order() {
		$this->login();
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
					"id_num"     => $id_nums[ $i ],
					"realname"   => $realnames[ $i ],
					"sex"        => $sex,
					"tel"        => $tels[ $i ],
					"source"     => self::ORDER_SOURCE_WEB,
					's_id'       => $s_id,
					'p_id'       => $p_id,
					'status'     => self::ORDER_STATUS_WAITING_PAY,
					'ctime'      => time(),
					"order_time" => strtotime($period['period'] . ($time == 'am' ? "9:00" : "15:00"))
				);
			}

			session("order", array (
				's_id'     => $s_id,
				'p_id'     => $p_id,
				"time"     => $time,
				'count'    => $num,
				'cost'     => $suite[ 'price_sex_' . $sex ] * $num,
				'status'   => self::ORDER_STATUS_WAITING_PAY,
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
					$this->success("预约成功!", U('Home/SuiteOrder/needpay'));
				} else {
					$this->error("出错!" . $db_info->getDbError());
				}
			}

		}

	}
	
	private function get_records($s_id) {
		$count = M("suite_order_info")->cache(true)->where("s_id=%d", $s_id)->count();
		$data  = M("suite_order_info")->field("realname,ctime,order_time")->cache(true)->where("s_id=%d", $s_id)->limit(20)->select();

		$this->assign("records", $data);
		$this->assign("records_count", $count);
	}

	private function get_comment($s_id) {
		$count = M("suite_comment")->cache(true)->where("s_id=%d", $s_id)->count();
		$data  = M("suite_comment")->alias("s")->join(C('DB_PREFIX') . "customer c on c.id=s.c_id")->field("c.realname,s.content,s.ctime,s.stars")->cache(true)->where("s.s_id=%d", $s_id)->limit(20)->select();

		$this->assign("comments", $data);
		$this->assign("comments_count", $count);
	}
}