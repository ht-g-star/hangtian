<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Admin\Model\BalanceReasonModel;
use Admin\Model\ChargeReasonModel;
use Admin\Model\ConsumeReasonModel;
use Think\Log;
use Think\Upload;
use Vendor\Sms\Sms;

/**
 *
 */
class SuitePeriodController extends AdminController {
	const STATUS_DELETED  = -1;
	const STATUS_DISABLED = 0;
	const STATUS_OK       = 1;
	
	const MODEL_NAME = "SuitePeriod";
	
	/**
	 * 套餐管理首页
	 *
	 */
	public function index() {
		$this->assign("meta_title", "预约时间设置");
		$s_id  = I("get.s_id");
		$db    = M(self::MODEL_NAME);
		$suite = M("suite")->cache("C_DB_SUITE")->alias("s")->field("s.id,s.title")->where("status > %d ", SuiteController::STATUS_DELETED)->select();
		$this->assign("suites", $suite);
		if ($s_id) {
			if (stripos($s_id, ',') !== false) {
				
			}else{
				$periods = $db->where("s_id=%d and period >= '%s'", $s_id, date("Y-m-d"))->order('period')->select();
				$this->assign("period", $periods);
			}
		
			if (IS_POST) {
				$action = I("post.action");
				if ($action == 'add') {
					$date   = I("post.date");
					$totals = I("post.total");
					$row    = array ();
					
					if (stripos($s_id, ',') !== false) {
						$_ids = explode(",", $s_id);
						foreach ($_ids as $_s_id) {
							foreach ($date as $day) {
								if ($db->where("s_id = %d and period = '%s'", $_s_id, $day)->find()) {
									continue;
								} else {
									$row[] = array (
										"period"    => $day,
										"s_id"      => $_s_id,
										"total_num" => $totals[ $day ],
										"ctime"     => time()
									);
								}
							}
						}
						
					} else {
						foreach ($date as $day) {
							if ($db->where("s_id = %d and period = '%s'", $s_id, $day)->find()) {
								$this->error("不能重复增加{$day}的库存!");
							}
							$row[] = array (
								"period"    => $day,
								"s_id"      => $s_id,
								"total_num" => $totals[ $day ],
								"ctime"     => time()
							);
						}
					}
					
					if ($db->addAll($row)) {
						$this->success("处理成功!");
					} else {
						$this->error("发生错误,不允许重复设置库存" . $db->getDbError());
					};
				} else if ($action == 'edit') {
					$totals = I("post.total");
					$sql    = "";
					foreach ($totals as $d => $t) {
						$sql .= $db->fetchSql(true)->where("s_id = %d and period = '%s'", $s_id, $d)->setField("total_num", $t) . ";\n";
					}
					if ($db->execute($sql) !== false) {
						$this->success("处理成功!");
					} else {
						$this->error("发生错误,不允许重复设置库存" . $db->getDbError());
					};
				}
				return;
			}
		}
		$this->assign("date", getdate());
		$this->display();
	}
	
	public function del() {
		$ids = I("get.ids");
		if (!$ids) {
			$this->error("请选择删除的条目");
		}
		$ids = explode(",", $ids);
		if (count($ids) <= 0) {
			$this->error("请选择删除的条目");
		}
		
		$res = M(self::MODEL_NAME)->where(array ("id" => array ("IN", $ids)))->setField('status', self::STATUS_DELETED);
		if ($res) {
			$loginuser = session("user_auth");
			addUserLog("删除套餐{$res['id']{$res['title']}}", $loginuser['uid']);
			$this->success("删除成功!");
		} else {
			$this->error(M()->getDbError());
		}
	}
	
	
}