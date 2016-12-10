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
class MemberFix2Controller extends AdminController {
	
	/**
	 * 导入初始余额
	 */
	
	public function index() {

		// $data = F("data_all_off");
		// if (!$data) {
		// 	$path = "./Data/all_off.xls";
		// 	if (stripos($path, ".xlsx") !== false) {
		// 		$data = excel_import($path, 'xlsx');
		// 	} else {
		// 		$data = excel_import($path);
		// 	}
		// 	unset($data[1]);
		// 	F("data_all_off",$data);
		// }
		
		
		//dump(($data));
		// $result=array();
		// foreach($data as $row){
		// 		$result[] = array(
		// 				'card_num' => trim($row['A']) ,
		// 				'money'		=>floatval(trim($row['B'])),
		// 				'reason'=>trim($row['C']),
		// 				'company'=>trim($row['D'])
		// 		);	
		// }
		// F("data_all_off",$result);
		
		// $db    = M("customer");

		// foreach ($data as &$value) {
		// 	if (!isset($value['id'])) {
		// 		$value['id']=M("new_card_log")->where("card_num='%s'",$value['card_num'])->order("id desc")->getField("id");
		// 	}

		// 	unset($value['card_num']);
		// }


		// F("data_all_off",$data);

		// $count=0;
		// foreach ($data as $value) {
		// 	M()->startTrans();
		// 	$value['cid']=$value['id'];
		// 	unset($value['id']);
		// 	if (M("consume_caiwu")->add($value)) {
		// 		$coung++;
		// 	}else{
		// 		M()->rollback();
		// 	}
			
		// }
		// dump($count);
		
		$data=M("consume_caiwu")->select();
		$count=0;
		foreach ($data as  $item) {
			$exist=M("consume_log_caiwudui")->fetchSql(false)
				->where("cid={$item['cid']} and money={$item['money']} and reason='{$item['reason']}' and remark like '%".$item['company']."%'")->find();
				
			if ($exist) {
				M()->startTrans();
				
				$s1=M("consume_caiwu")->delete($item['id']);
				$s2=M("consume_log_caiwudui")->delete($exist['id']);
				if ($s1&&$s2) {
					$count++;
					M()->commit();
				}else{
					M()->rollback();
				}
			}
			
		}
		dump($count);
		
		
	}
	
	public function index2() {
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