<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 1010422715@qq.com  All rights reserved.
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Admin\Model\ChargeReasonModel;
use Admin\Model\ConsumeTypeModel;
use Think\Page;

/**
 * 后台数据统计控制器
 *
 *
 * php获取今日开始时间戳和结束时间戳
 *
 * $a=date('Ymd',$qtime);/*格式时间戳为 20141024
 *
 * $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
 *
 * $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
 *
 * php获取本月起始时间戳和结束时间戳
 *
 * $beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
 * $endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
 * PHP mktime() 函数用于返回一个日期的 Unix 时间戳。
 */
class MemberBalanceReportController extends AdminController {
	
	public function index() {
		$this->meta_title = '会员消费统计';
		$title            = "会员消费统计 ";
		$this->assign('title', $title);
		
		$where      = " where 1=1 ";
		$begin_time = I('begin_time') ? I('begin_time') : date("Y") . "-01-01";
		$end_time   = I('end_time') ? I('end_time') : date("Y") . "-12-31";
		
		if ($begin_time) {
			$where .= " and cTime >=" . strtotime($begin_time);
		}
		if ($end_time) {
			$where .= " and cTime <=" . strtotime($end_time);
		}
		
		$pre      = C("DB_PREFIX");
		$data     = M()->query("select sum(money) as sum,type  from {$pre}consume_log $where group by type");
		$new_data = array ();
		$sum      = 0;
		foreach ($data as &$row) {
			if (!$row['type']) {
				$row['type'] = '未分类';
			}
			$sum += $row['sum'];
			$new_data[] = array ($row['type'], intval($row['sum']));
		}
		$this->assign('sum', $sum);
		$this->assign("data2", $data);
		$this->assign("data", json_encode($new_data));
		
		$types = M("consume_type")->where("status=1")->getField("name", true);
		$this->assign("types", $types);
		$this->display();
	}
	
	
	public function logs() {
		$this->assign("types", ConsumeTypeModel::getAll(1));
		$this->assign('meta_title', '会员消费明细');
		$where = array ();
		if (I("starttime")) {
			$where['cTime'][] = array ("egt", strtotime(I("starttime")));
		}
		if (I("endtime")) {
			$where['cTime'][] = array ("elt", strtotime(I("endtime")));
		}
		if (I('type')) {
			$where['type'] = I('type');
		}
		$search = I('search');
		if ($search) {
			$ids = M("customer")->where("realname like '%{$search}%' or card_num='{$search}'")->getField("id", true);
			if ($ids) {
				$where['cid'] = array ("in", $ids);
			}
		}
		C('LIST_ROWS', 20);
		$list = $this->lists('consume_log', $where, 'id desc');
		$this->assign('_list', $list);
		$this->display();
	}
	
	public function out() {
		$where = array ();
		if (I("starttime")) {
			$where['l.cTime'][] = array ("egt", strtotime(I("starttime")));
		}
		if (I("endtime")) {
			$where['l.cTime'][] = array ("elt", strtotime(I("endtime")));
		}
		$search = I('search');
		if ($search) {
			$ids = M("customer")->where("realname like '%{$search}%' or card_num='{$search}'")->getField("id", true);
			if ($ids) {
				$where['l.cid'] = array ("in", $ids);
			}
		}
		$pre  = C("DB_PREFIX");
		$data = M("consume_log")->alias("l")->field("l.id,c.realname,c.card_num,l.remark,l.money,l.pay_type,l.reason,type,l.cTime")->join("LEFT JOIN {$pre}customer c on c.id=l.cid")->where($where)->limit(12000)->order("l.id desc")->select();
		foreach ($data as &$row) {
			$row['ctime'] = date("Y-m-d H:i:s", $row['ctime']);
		}
		
		exportExcel("会员消费导出", array (
			array ("id", "id"),
			array ("realname", "顾客"),
			array ("card_num", "卡号"),
			array ("remark", "详情"),
			array ("money", "金额"),
			array ("pay_type", "支付方式"),
			array ("reason", "理由"),
			array ("type", "类型"),
			array ("ctime", "执行时间"),
		), $data);
	}
	
	public function out2() {
		$where = array ();
		if (I("starttime")) {
			$where['l.cTime'][] = array ("egt", strtotime(I("starttime")));
		}
		if (I("endtime")) {
			$where['l.cTime'][] = array ("elt", strtotime(I("endtime")));
		}
		if (I('type')) {
			$where['l.type'] = I('type');
		}
		$search = I('search');
		if ($search) {
			$ids = M("customer")->where("realname like '%{$search}%' or card_num='{$search}'")->getField("id", true);
			if ($ids) {
				$where['l.cid'] = array ("in", $ids);
			}
		}
		$pre  = C("DB_PREFIX");
		$data = M("consume_log")->alias("l")->field("l.id,c.realname,c.card_num,l.remark,l.money,l.reason,l.type,l.cTime")->join("LEFT JOIN {$pre}customer c on c.id=l.cid")->where($where)->limit(5000)->order("l.id desc")->select();
		foreach ($data as &$row) {
			$row['ctime'] = date("Y-m-d H:i:s", $row['ctime']);
		}
		
		exportExcel("会员消费导出", array (
			array ("id", "id"),
			array ("realname", "顾客"),
			array ("card_num", "卡号"),
			array ("remark", "详情"),
			array ("money", "金额"),
			array ("reason", "理由"),
			array ("type", "类型"),
			array ("ctime", "执行时间"),
		), $data);
	}
	
	public function out3() {
		$where = array ();
		if (I("starttime")) {
			$where['l.cTime'][] = array ("egt", strtotime(I("starttime")));
		}
		if (I("endtime")) {
			$where['l.cTime'][] = array ("elt", strtotime(I("endtime")));
		}
		$search = I('search');
		if ($search) {
			$ids = M("customer")->where("realname like '%{$search}%' or card_num='{$search}'")->getField("id", true);
			if ($ids) {
				$where['l.cid'] = array ("in", $ids);
			}
		}
		$pre  = C("DB_PREFIX");
		$data = M("charge_log")->alias("l")->field("l.id,c.realname,c.card_num,l.remark,l.money,l.reason,l.cTime")->join("LEFT JOIN {$pre}customer c on c.id=l.cid")->where($where)->limit(5000)->order("l.id desc")->select();
		foreach ($data as &$row) {
			$row['ctime'] = date("Y-m-d H:i:s", $row['ctime']);
		}
		
		exportExcel("会员充值记录导出", array (
			array ("id", "id"),
			array ("realname", "顾客"),
			array ("card_num", "卡号"),
			array ("remark", "详情"),
			array ("money", "金额"),
			array ("reason", "理由"),
			array ("ctime", "执行时间"),
		), $data);
	}
	
	
	public function charge_logs() {
		$this->assign("types", ChargeReasonModel::getAllReason());
		$this->assign('meta_title', '会员充值明细');
		$where = array ();
		if (I("starttime")) {
			$where['cTime'][] = array ("egt", strtotime(I("starttime")));
		}
		if (I("endtime")) {
			$where['cTime'][] = array ("elt", strtotime(I("endtime")));
		}
		$search = I('search');
		if ($search) {
			$ids = M("customer")->where("realname like '%{$search}%' or card_num='{$search}'")->getField("id", true);
			if ($ids) {
				$where['cid'] = array ("in", $ids);
			}
		}
		C('LIST_ROWS', 20);
		$list = $this->lists('charge_log', $where, 'id desc');
		$this->assign('_list', $list);
		$this->display();
	}
}

