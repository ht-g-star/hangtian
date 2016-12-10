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
use Home\Controller\SuiteController;
use Think\Log;
use Think\Upload;
use Vendor\Sms\Sms;

/**
 *
 */
class SuiteOrderController extends AdminController {
	const STATUS_DELETED = -1;
	
	const STATUS_CANCEL      = 0;
	const STATUS_WAITING_PAY = 1;
	const STATUS_PAYED       = 10;
	const STATUS_MARK_REG    = 11;
	const STATUS_FINISH      = 20;
	
	const MODEL_NAME = "SuiteOrder";
	
	/**
	 * 套餐订单管理首页
	 *
	 */
	public
	function index() {
		$this->assign("meta_title", "套餐订单管理");
		$action = I("get.type");
		$this->assign("action", $action);
		$db  = M(self::MODEL_NAME);
		$pre = C("DB_PREFIX");
		if (I('get.type') == 'ajax') {
			echo json_encode(array (
				                 "data" => $db->alias("o")->field("o.order_no,o.cost,o.status,o.ctime,s.title,p.period,o.time,c.realname,c.card_num,i.realname as name2,i.tel as tel2,i.id_num as id_num2,i.sex as sex2")
				                              ->join("left join {$pre}suite_period p on p.id=o.p_id")
				                              ->join("left join {$pre}suite s on s.id=o.s_id")
				                              ->join("left join {$pre}customer c on c.id=o.c_id")
				                              ->join("left join {$pre}suite_order_info i on i.o_id=o.id")
				                              ->where("o.status > " . self::STATUS_DELETED . " and (o.c_id is not null or o.c_id !=0) ")
				                              ->order("o.ctime desc")
				                              ->limit(2000)
				                              ->select()
			                 ));
		} else if (I('get.type') == 'reg') {
			$ids_str = I("get.ids");
			if (!$ids_str) {
				$this->error("请选择设置的条目");
			}
			$ids = explode(",", $ids_str);
			if (count($ids) <= 0) {
				$this->error("请选择设置的条目");
			}
			$res     = M(self::MODEL_NAME)->where(array ("order_no" => array ("IN", $ids)))->setField('status', self::STATUS_MARK_REG);
			$s_ids   = M(self::MODEL_NAME)->where(array ("order_no" => array ("IN", $ids)))->getField('id', true);
			$mobiles = M(self::MODEL_NAME)->distinct(true)->field("c.mobile,o.order_no")
			                              ->alias('o')->join("left join {$pre}customer c on c.id=o.c_id")
			                              ->where(array ("order_no" => array ("IN", $ids)))->select();
			M("suite_order_info")->where(array ("o_id" => array ("IN", $s_ids)))->setField('status', OrderInfoController::STAUTS_REG);
			if ($res) {
				$loginuser = session("user_auth");
				addUserLog("设置订单号:{$ids_str}为已登记", $loginuser['uid']);
				//发送短信
				$sms = new Sms();
				foreach ($mobiles as $m) {
					$sms->send($m['mobile'], "您预约的体检订单已经成功登记，订单号为{$m['order_no']}，请按时体检。");
				}
				$this->success("设置成功!");
			} else {
				$this->error(M()->getDbError());
			}
		} else if (I('get.type') == 'finish') {
			$ids_str = I("get.ids");
			if (!$ids_str) {
				$this->error("请选择设置的条目");
			}
			$ids = explode(",", $ids_str);
			if (count($ids) <= 0) {
				$this->error("请选择设置的条目");
			}
			$res   = M(self::MODEL_NAME)->where(array ("order_no" => array ("IN", $ids)))->setField('status', self::STATUS_FINISH);
			$s_ids = M(self::MODEL_NAME)->where(array ("order_no" => array ("IN", $ids)))->getField('id', true);
			M("suite_order_info")->where(array ("o_id" => array ("IN", $s_ids)))->setField('status', OrderInfoController::STAUTS_FINISH);
			if ($res) {
				$loginuser = session("user_auth");
				addUserLog("设置订单号:{$ids_str}为已完成", $loginuser['uid']);
				$this->success("设置成功!");
			} else {
				$this->error(M()->getDbError());
			}
		} else {
			$this->display();
		}
	}
	
	public
	function del() {
		$ids = I("get.ids");
		if (!$ids) {
			$this->error("请选择删除的条目");
		}
		$ids = explode(",", $ids);
		if (count($ids) <= 0) {
			$this->error("请选择删除的条目");
		}
		S('C_DB_SUITE', null);
		$res = M(self::MODEL_NAME)->where(array ("id" => array ("IN", $ids)))->setField('status', self::STATUS_DELETED);
		if ($res) {
			$loginuser = session("user_auth");
			addUserLog("删除套餐订单{$res['id']{$res['title']}}", $loginuser['uid']);
			$this->success("删除成功!");
		} else {
			$this->error(M()->getDbError());
		}
	}
	
	public
	function import() {
		$this->assign("meta_title", "体检批量导入");
		if (IS_AJAX) {
			set_time_limit(200);
			$result              = session("import_order");
			$suite_order_db      = M("suite_order");
			$suite_period_db     = M("suite_period");
			$suite_order_info_db = M("suite_order_info");
			$loginuser           = session("user_auth");
			
			$order_info = array ();
			if (!$result) {
				$this->error("数据出错,请重新导入");
			}
			foreach ($result as $row) {
				//检查数据
				$suite = M("suite")->where("code='%s'", $row['code'])->find();
				if (!$suite) {
					$this->error("错误的套餐编码:{$row['code']}");
				}
				$period = $suite_period_db->where("period='%s' and s_id=%d", date("Y-m-d", strtotime($row['period'])), $suite['id'])->find();
				if (!$period) {
					$this->error("{$suite['title']}-{$suite['code']}这个套餐不存在{$row['period']}的预约时间.");
				}
				$order_info[] = array (
					'id_num'     => $row['id_num'],
					'sex'        => isset($row['sex']) ? $row['sex'] : -1,
					'realname'   => $row['realname'],
					'tel'        => isset($row['mobile']) ? $row['mobile'] : 0,
					'source'     => SuiteController::ORDER_SOURCE_ADMIN,
					's_id'       => $suite['id'],
					'p_id'       => $period['id'],
					'ctime'      => time(),
					'status'     => SuiteController::ORDER_STATUS_OK,
					"order_time" => strtotime($period['period'] . (trim($row['time']) == 'am' ? "9:00" : "15:00"))
				);
			}
			
			//1.创建订单
			$order_no    = build_order_no();
			$order       = array (
				'count'    => count($result),
				'cost'     => 0,
				'status'   => 20,
				'ctime'    => time(),
				'order_no' => $order_no,
				'c_id'     => 0// 0 为后台创建订单
			);
			$order['id'] = $suite_order_db->add($order);
			
			foreach ($order_info as &$line) {
				$line['o_id'] = $order['id'];
			}
			
			$result = $suite_order_info_db->addAll($order_info);
			if ($result) {
				R("Api/SuiteOrderSend/send", array ('order_id' => $order['id']));
				addUserLog("导入批量体检文件,已经执行成功.", $loginuser['uid']);
				session("import_consume", null);
				$this->success("执行成功.");
			} else {
				$this->error("执行失败,请告知错误:" . M()->getError());
			}
			
			
		} else if (IS_POST) {
			/* 上传文件 */
			$config = C('MEMBER_UPLOAD');
			$Upload = new Upload($config);
			$info   = $Upload->upload();
			if (isset($info['consume'])) { //文件上传成功，记录文件信息
				$path = "." . substr($config['rootPath'], 1) . $info['consume']['savepath'] . $info['consume']['savename'];    //在模板里的url路径
				if (stripos($path, ".xlsx") !== false) {
					$data = excel_import($path, 'xlsx');
				} else {
					$data = excel_import($path);
				}
				unset($data[1]);
				$db      = M("customer");
				$lists   = array ();
				$id_nums = array ();
				foreach ($data as $row) {
					if ($row['B'] == '') {
						continue;
					}
					foreach ($row as $key => $value) {
						if (is_null($value)) {
							$row[ $key ] = "";
						} else {
							$row[ $key ] = trim((string)$value);
						}
					}
					$id_nums[]          = $row['B'];
					$lists[ $row['B'] ] = array (
						"card_num" => $row['A'],
						"id_num"   => $row['B'],
						"realname" => $row['C'],
						"code"     => $row['D'],
						"cost"     => $row['E'],
						"period"   => $row['F'],
						"time"     => $row['G']
					);
				}
				
				if (empty($id_nums)) {
					$this->error("未检测到数据,请检查数据,身份证号码为必选项目");
				}
				$count  = $result = $db->field("id")->where(array ('id_num' => array ("in", $id_nums)))->count();
				$result = $db->where(array ('id_num' => array ("in", $id_nums)))
				             ->getField("id_num,id,realname,sex,card_num,company,dept,position,balance,mobile,mobile as tel");
				
				foreach ($lists as &$member) {
					if (!empty($lists[ $member['id_num'] ]) && !empty($member) && !empty($result[ $member['id_num'] ])) {
						$member = array_merge($member, $result[ $member['id_num'] ]);
					}
				}
				
				if ($count != count($lists)) {
					$this->assign("need_verify", true);
				}
				session("import_order", $lists);
				$this->assign("result", $lists);
				$this->assign("meta_title", "数据确认");
				$this->display("confirm");
			} else {
				$this->error($Upload->getError());
				return false;
			}
		} else {
			$this->display();
		}
	}
	
	
	public
	function manual() {
		$this->assign("meta_title", "批量发送信息");
		$db       = M('customer');
		$companys = $db->distinct(true)->field('company')->cache(true)->select();
		$this->assign("companys", $companys);
		
		if (IS_AJAX) {
			$ids = I("post.ids", null);
			if (empty($ids)) {
				$this->error("至少选择一个!");
				return;
			}
			$loginuser = session("user_auth");
			R("Api/SuiteOrderSend/send2", array ('ids' => $ids));
			addUserLog("手工发送体检名单至汉立系统", $loginuser['uid']);
			$this->success("执行成功.");
		} else {
			$this->display();
		}
	}
	
}