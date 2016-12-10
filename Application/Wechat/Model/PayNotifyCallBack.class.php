<?php
/**
 * Created by PhpStorm.
 * User: litao
 * Date: 16/1/17
 * Time: 22:12
 */

namespace Wechat\Model;


use Home\Controller\SuiteController;
use Think\Log;
use Think\Pay\Pay;

class PayNotifyCallBack extends \WxPayNotify {
	//查询订单
	public function Queryorder($transaction_id) {
		$input = new \WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = \WxPayApi::orderQuery($input);
		Log::write(print_r($result, true));
		if (array_key_exists("return_code", $result)
		    && array_key_exists("result_code", $result)
		    && $result["return_code"] == "SUCCESS"
		    && $result["result_code"] == "SUCCESS"
		) {
			
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg) {
//		Log::write(print_r($data, true));
		
		Log::write("wechatPayCallBackTest:" . $data);
		
		$notfiyOutput = array ();
		
		if (!array_key_exists("transaction_id", $data)) {
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if (!$this->Queryorder($data['transaction_id'])) {
			$msg = "订单查询失败";
			return false;
		} else {
			$orderid = $data['attach'];
			$order   = M("pay")->where("out_trade_no='%s'", $orderid)->find();
			Log::write("订单:" . print_r($order, true));
			if ($order) {
				if (stripos($orderid, "CHARGE") !== false) {//充值
					M()->startTrans();
					M("pay")->where("out_trade_no='%s'", $orderid)->save(array (
						                                                     'update_time' => time(),
						                                                     'param'       => json_encode($data),
						                                                     'status'      => 2,
						                                                     'type'        => \Think\Pay::TYPE_WECHAT
					                                                     ));
					$money = $order['money'];
					$s1    = M("charge_log")->add(array (
						                              'uid'     => 0,
						                              'cid'     => $order['uid'],
						                              'remark'  => "用户通过微信自主充值" . $money,
						                              'cTime'   => time(),
						                              'reason'  => 2,
						                              'money'   => $money,
						                              'orderid' => $orderid
					                              ));
					$s2    = M("customer")->where("id=%d", $order['uid'])->setInc("balance", $money);
					if ($s1 && $s2) {
						M()->commit();
					} else {
						M()->rollback();
						Log::write("错误的充值!" . print_r($data, true));
					}
					$uid = is_login();
					
				} else {
					M("pay")->where("out_trade_no='%s'", $orderid)->save(array (
						                                                     'update_time' => time(),
						                                                     'param'       => json_encode($data),
						                                                     'status'      => 2,
						                                                     'type'        => \Think\Pay::TYPE_WECHAT
					                                                     ));
					M("order")->where("tag='%s'", $orderid)->save(array (
						                                              'status' => 1,
						                                              'ispay'  => 1
					
					                                              ));
					M("suite_order")->where("order_no='%s'", $orderid)->save(array (
						                                                         'status' => SuiteController::ORDER_STATUS_OK,
					                                                         ));
					if (strlen($orderid) == strlen("2016012251101505")) {
						$this->sendSuiteMail($orderid);
						$suite_order = M("suite_order")->where("order_no='%s'", $orderid)->find();
						add_score($suite_order['c_id'], $suite_order['order_no'], $suite_order['cost']);
					} else {
						$this->sendMail($orderid);
						$order = M('order')->where(" tag='%s' or orderid='%s'", $orderid, $orderid)->find();
						add_score($order['uid'], $order['tag'], $order['total_money']);
					}
				}
				
			} else {
				Log::write("订单号错误:{$orderid}");
			}
			
		}
		return true;
	}
	
	private function sendMail($orderid) {
		$title   = "订单提醒";
		$content = '收到了新的商城订单，订单编号:' . $orderid;
		if (C('MAIL_PASSWORD')) {
			SendMail(C('MAIL_RECEIVER'), $title, $content);
		}
	}
	
	private function sendSuiteMail($orderid) {
		$title   = "订单提醒";
		$content = '收到了新的体检预约，编号:' . $orderid;
		if (C('MAIL_PASSWORD')) {
			SendMail(C('MAIL_RECEIVER'), $title, $content);
		}
	}
	
	
}