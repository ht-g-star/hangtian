<?php
/**
 * +----------------------------------------------------------------------
 * |统一支付平台 —— 入口
 * +----------------------------------------------------------------------
 * |Copyright (c) 2016 http://www.wx738.com All rights reserved.
 * +----------------------------------------------------------------------
 * |Author: g-star <gucy@wx738.com> <http://www.wx738.com>
 * +----------------------------------------------------------------------
 */

namespace Pay\Logic;
use Think\Model;
use Think\Pay;
use Think\Log;

/**
 * 文档模型子模型 - 文章模型
 */
class OrderLogic extends Model{
	private $pay,$order,$uid,$login_user;
	
	/**
	 * 必须初始化才能使用这个类
	 * @param unknown $uid
	 * @param unknown $login_user
	 */
	public function __construct($uid,$login_user){
		parent::__construct();
		$this->pay = M('Pay');
		$this->order = M('order');
		$this->uid = $uid;
		$this->login_user = $login_user;
	}
	
	public function startTrans(){
		M()->startTrans();
		Log::write("操作者：{$this->uid},开始处理本次订单请求。");
	}
	
	
	public function rollback(){
		M()->rollback();
		Log::write("操作者：{$this->uid},本次订单请求失败回滚。\n");
	}
	
	public function commit(){
		M()->commit();
		Log::write("操作者：{$this->uid},本次订单请求成功。\n");
	}
	
	/**
	 * 多个订单同时提交
	 * @param unknown $idArr
	 */
	public function requestManyOrder($idArr){
		try {
			//提交过来的多个订单的总价
			$total_order = 0;
			 
			$orderList = array();
			/**
			 * 将所有的订单信息查出来 并记录在数组  $orderList 中
			*/
			foreach ($idArr as $key => $val){
				$map = array(
						'uid'=>array('EQ',$this->uid),
						'_query'=>"orderid={$val}&tag={$val}&_logic=or",
				);
				$orderTmp       = $this->order->where($map)->find();
				if(!$orderTmp){
					Log::write("操作者{$this->uid},不存在订单号：{$val}");
					return array('rst'=>FALSE,'prompt'=>"不存在订单号：{$val}");					
				}
				$total_order += $orderTmp['total_money'];
				array_push($orderList, $orderTmp);
			}
						
			//账户余额
			$balance     = $this->login_user['balance'];			
			/*
			 * 判断余额是否够支付
			*/
			if ($balance < $total_order) {
				return array('rst'=>FALSE,'prompt'=>"余额不足!");
			}
			
			// 开始事务处， 准备开始付钱			 
			self::startTrans();
			//从账户余额中减去相应的金额
			$rst = $orderList['total_order'] == 0?1:M("customer")->where("id=%d", $this->uid)->setDec("balance", $orderList['total_order']);
			if(!$rst){
				Log::write("支付出错,余额减法错误。");
				self::rollback();//出错事务回滚
				return array('rst'=>FALSE,'prompt'=>"出错,请稍后重试!");
			}
			
			//循环每一笔请求订单并通过余额支付钱			 
			foreach ($total_order as $key => $val){
				$rst = self::requestOneOrder($val);
				if(!$rst['rst']){
					self::rollback();//出错事务回滚
					return $rst;
				}
			}
			
			self::commit();
			
			return array('rst'=>TRUE);
		} catch (\Exception $e) {
			Log::write("Pay/Logic/Order.class.php中方法requestOrder抛出错误，订单号：".serialize($idArr));
			return array('rst'=>FALSE,'prompt'=>"出错,请稍后重试!");
        	//echo $e->getMessage();
        }
	}
	
	/**
	 * 对其中的一个订单号进行处理
	 * @param unknown $order
	 * @return multitype:boolean |multitype:boolean string
	 */
	private function requestOneOrder($order){
		try {
			
			$pay_data = array (
					'out_trade_no' => $order['tag'],
					'money'        => $order['total_money'],
					'status'       => 1,
					'create_time'  => time(),
					'update_time'  => time(),
					'total'        => $order['total_money'],
					'uid'          => $this->uid,
					'type'         => Pay::TYPE_BALANCE
			);
			 
			Log::write("支付表“Pay”中tag：{$order['tag']}");
			$exist = $this->pay->where("out_trade_no='%s'", $order['tag'])->find();
			Log::write("支付表“Pay”中原始." . serialize($exist));
			Log::write("支付表“Pay”中更新." . serialize($pay_data));
			 
			if ($exist) {
				$s1 = $this->pay->where("out_trade_no='%s'", $order['tag'])->save($pay_data);
			} else {
				Log::write("上述更新数据为支付表“Pay”中新增tag：{$order['tag']}");
				$s1 = $this->pay->add($pay_data);
			}
			 
			$order_data = array (
					'is_pay'      => 2,
					'status'      => 1,
					'update_time' => NOW_TIME
			);
			$s2 = $this->order->where("id='%d'", $order['id'])->save($order_data);
			 
			$consume_log_data = array (
					'uid'      => $this->uid,
					'cid'      => $this->uid,
					'cTime'    => time(),
					'card_num' => $this->login_user['card_num'],
					'reason'   => "购物",
					'type'     => "商城",
					"money"    => $order['total_money'],
					'pay_type' => '余额',
					'remark'   => "会员{$this->login_user['realname']}购物消费了{$order['total_money']},订单号:{$order['orderid']}"
			);
			$s3 = M("consume_log")->add($consume_log_data);
			
			if ($s1 && $s2 && $s3) {
				return array('rst'=>TRUE);
			}else{
				Log::write("支付出错,结果: {$s1} && {$s2} && {$s3}");
				return array('rst'=>FALSE,'prompt'=>"出错,请稍后重试!");
			}
		} catch (\Exception $e) {
			Log::write("Pay/Logic/Order.class.php中方法requestOneOrder抛出错误，订单信息：".serialize($order));
			return array('rst'=>FALSE,'prompt'=>"出错,请稍后重试!");	
		}		
	}
	
	
	
	
	

}
