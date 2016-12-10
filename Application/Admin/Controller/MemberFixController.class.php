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
use Admin\Model\ConsumeTypeModel;
use Think\Log;
use Think\Upload;
use Vendor\Sms\Sms;

/**
 * 后台用户控制器
 *
 */
class MemberFixController extends AdminController {
	
	/**
	 * 导入初始余额
	 */
	
	/*
	public function index() {
		$data = session("_temp_data_811");
		if (!$data) {
			$path = "./Data/811.xls";
			if (stripos($path, ".xlsx") !== false) {
				$data = excel_import($path, 'xlsx');
			} else {
				$data = excel_import($path);
			}
			unset($data[1]);
			session("_temp_data_811", $data);
		}
		
		$db    = M("customer");
		$log   = M("consume_log");
		$count = 0;
		if (count($data) <= 0) {
			exit(dump($data));
		}
		$_temp = $data;
		foreach ($data as $key => $row) {
			$count++;
			if (is_object($row['H'])) {
				$row['H'] = trim((string)$row['H']);
			}
			if (is_object($row['J'])) {
				$row['J'] = trim((string)$row['J']);
			}
			
			if (strlen($row['H']) == 18) {
				$row['H'] = strtoupper(trim($row['H']));
				$c        = $db->where("id_num='%s'", $row['H'])->field("id,card_num")->find();
				if ($c) {
					$db->where("id=%d", $c['id'])->setField("init_balance", $row['J']);
					$log->add(array (
						          'uid'      => 3,
						          'cid'      => $c['id'],
						          'cTime'    => 1457060969,
						          'card_num' => $c['card_num'],
						          'remark'   => '开卡初始化金额为:' . $row['J'],
						          'money'    => 0,
						          'reason'   => '开卡',
					          ));
				}
			}
			unset($_temp[ $key ]);
			if ($count >= 500) {
				session("_temp_data_811", $_temp);
				break;
			}
		}
		dump(count($_temp));
	}
	*/
	
	public function index() {
		$data  = M("customer")->where("init_balance > 0  ")->select();
		$log   = M("consume_log");
		$log2  = M("charge_log");
		$wrong = array ();
		$count = 0;
		foreach ($data as $key => $item) {
			$records  = $log->where("cid=%d", $item['id'])->select();
			$records2 = $log2->where("cid=%d", $item['id'])->select();
			$total    = 0;
			$total2   = 0;
			foreach ($records as $record) {
				$total += $record['money'];
			}
			foreach ($records2 as $record) {
				$total2 += $record['money'];
			}
			
			if (intval($total) - intval($total2) != intval($item['init_balance'] - $item['balance'])) {
				$wrong[] = array (
					"id"   => $item['id'],
					"diff" => intval($total) - intval($total2) - ($item['init_balance'] - $item['balance'])
				);
				
				$count++;
			}
			
			if ($count > 500) {
				break;
			}
		}
		
		dump($wrong);
		exit;
		
	}
	
	public function fix2() {
		$log      = M("user_log");
		$log2     = M("consume_log");
		$customer = M("customer");
		$data     = $log->where("title like '%被批量扣%'")->select();
		foreach ($data as $item) {
			$content = $item['title'];
			$_temp   = explode(",", $content);
			$cid     = explode(":", $_temp[0]);
			$cid     = trim($cid['1']);
			$money   = trim(str_replace("被批量扣", '', $_temp[1]));
			$time    = $item['create_time'];
			if (!$log2->where("cid=%d and money=%d", $cid, $money)->find()) {
				$realname = $customer->where("id=%d", $cid)->getField("realname");
				$log2->add(array (
					           'uid'    => $item['uid'],
					           'cid'    => $cid,
					           'cTime'  => $time,
					           'remark' => "会员ID:{$realname}批量操作-{$money},理由是批量扣款,会员ID26",
					           'money'  => $money,
					           'reason' => '批量扣款'
				           ));
			}
		}
	}
	
	public function fix3() {
		$orders = M("order")->where("status=%d", 6)->select();
		$log    = M("consume_log");
		$count  = 0;
		foreach ($orders as $order) {
			if ($log->where("remark like '%订单号:{$order['orderid']}%'")->delete()) {
				$count++;
			}
		}
		dump($count);
	}
	
	public function fix4() {
		$customer = M("customer");
		$customer->where("id>=922 and id<=930")->setField("init_balance", 0);
	}
	
	
	public function fixPay() {
		$pay_db = M("pay");
//		$pay_db->where("uid=6431 and money=493.80")->delete();
//		exit;
		$data = $pay_db->where("money=493.80")->limit(1000)->select();
		dump($data);
		exit;
		$order = M("charge_log");
		foreach ($data as $item) {
			$o = $order->where("orderid='%s'", $item['out_trade_no'])->find();
			if ($o) {
				dump($pay_db->fetchSql(false)->where("out_trade_no='%s'", $item['out_trade_no'])->save(array (
					                                                                                       "money" => $o['money'],
					                                                                                       'total' => $o['money']
				                                                                                       )));
			}
			
		}
		echo $pay_db->where("money=493.80")->count();
	}
	
	/*
	public function fixJ() {
		$order  = M("jipiao_order");
		$pay_db = M("pay");
		$data   = $order->limit(1000)->select();
		
		foreach ($data as $item) {
			$o = $pay_db->where("out_trade_no='%s'", $item['orderid'])->find();
			if ($o) {
				dump($pay_db->fetchSql(false)->where("out_trade_no='%s'", $item['orderid'])->save(array (
					                                                                                  "money" => floatval($item['totalorderprice']),
					                                                                                  'total' => floatval($item['totalorderprice'])
				                                                                                  )));
			}
			
		}
	}
	*/
}