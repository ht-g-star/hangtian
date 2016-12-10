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
class OrderInfoController extends AdminController {
	
	const STAUTS_REG    = 10;
	const STAUTS_FINISH = 20;
	
	/**
	 * 预约管理首页
	 *
	 */
	public function index() {
		$this->assign("meta_title", "预约信息");
		$db = M('SuiteOrderInfo');
		
		if (I('get.type') == 'ajax') {
			$starttime = I("get.starttime", 0, 'trim');
			$endtime   = I("get.endtime", 0, 'trim');
			$map       = array ("status" => self::STAUTS_REG);
			if ($starttime) {
				$map['order_time'][] = array ("egt", strtotime($starttime));
			}
			if ($endtime) {
				$map['order_time'][] = array ("elt", strtotime($endtime));
			}

//			$pay_orders = $order_db->where("status=%d", SuiteController::ORDER_STATUS_OK)->getField("id", true);
			$data = $db->field("id,realname,tel,id_num,source,status,sex,status,ctime,order_time,s_id")
			           ->where($map)
			           ->order("id desc")
			           ->limit(2000)
			           ->select();
			if (empty($data)) {
				$data = array ();
			}
			foreach ($data as &$_row) {
				$_suite           = M("suite")->cache(true)->find($_row['s_id']);
				$_row['card_num'] = M("customer")->cache(true)->where("id_num='%s'", $_row['id_num'])->getField("card_num");
				$_row['title']    = $_suite['title'];
			}
			echo json_encode(array (
				                 "data" => $data
			                 ));
		} else {
			$this->display();
		}
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
		
		$res = M('SuiteOrderInfo')->where(array ("id" => array ("IN", $ids)))->delete();
		if ($res) {
			$loginuser = session("user_auth");
			addUserLog("订单{$res['id']{$res['name']}}", $loginuser['uid']);
			$this->success("删除成功!");
		} else {
			$this->error(M()->getDbError());
		}
	}
	
	public function export() {
		$db       = M('SuiteOrderInfo');
		$order_db = M("suite_order");
		$c_db     = M("customer");
		
		$starttime = I("get.starttime", 0, 'trim');
		$endtime   = I("get.endtime", 0, 'trim');
		
		$map = array ("status" => 10);
		if ($starttime) {
			$map['order_time'][] = array ("egt", strtotime($starttime));
		}
		if ($endtime) {
			$map['order_time'][] = array ("elt", strtotime($endtime));
		}
		$data = $db->field("id,realname,tel,id_num,sex,status,ctime,order_time")
		           ->where($map)->limit(500)->select();
		
		$xlsCell = array (
			array ('id', '编号'),
			array ('card_num', '会员号'),
			array ('realname', '姓名'),
			array ('tel', '电话'),
			array ('id_num', '身份证号'),
			array ('sex', '性别'),
			array ('ctime', '创建时间'),
			array ('order_time', '预约时间'),
		);
		foreach ($data as &$row) {
			$row['card_num']   = M("customer")->cache(true)->where("id_num='%s'", $row['id_num'])->getField("card_num");
			$row['sex']        = $row['sex'] == 1 ? "男" : "女";
			$row['ctime']      = date("Y-m-d H:i:s", $row['ctime']);
			$row['order_time'] = date("Y-m-d H:i:s", $row['order_time']);
		}
		
		exportExcel("体检预约导出", $xlsCell, $data);
	}
	
	
}