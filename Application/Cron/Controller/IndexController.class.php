<?php
namespace Cron\Controller;

use Think\Controller;
use Think\Log;

class IndexController extends Controller {
	public function index() {
		session_write_close();
		ignore_user_abort();
		set_time_limit(0);
		//订单清理
		$this->order_auto_cancel();
		$this->pay_uid_fix();
		$this->pay_consume_fix();
		$this->consume_log_fix();
		$this->order_auto_confirm();
		exit;
	}
	
	public function order_auto_cancel() {
		$thirty_min_ago = NOW_TIME - 30 * 60;
		$count          = M("order")->where("status=-1 and create_time<%d and ordertype=1", $thirty_min_ago)->save(array (
			                                                                                                           "status" => 6,
			                                                                                                           'ispay'  => 0
		                                                                                                           ));
		$count2         = M("shopcart")->where("create_time<%d", NOW_TIME - 7 * 24 * 60)->delete();
		Log::write("已经自动取消掉{$count}条订单.清理了{$count2}购物车");
		
		$sixty_min_ago = NOW_TIME - 60 * 50;//50分钟清理一次机票
		$count3        = M("jipiao_order")->where("orderstatus='WAIT_PAY' and addtime<%d", $sixty_min_ago)->save(
			array (
				"orderstatus" => "CANCELED"
			)
		);
		Log::write("已经自动取消掉{$count3}条机票订单");
	}
	
	public function pay_uid_fix() {
		$data = M("pay")->where("uid=0 and status!=0")->select();
		foreach ($data as $item) {
			$exist = M("order")->where("tag='{$item['out_trade_no']}'")->find();
			if ($exist) {
				M("pay")->where("id=%d", $item['id'])->setField("uid", $exist['uid']);
			} else {
				$exist = M("suite_order")->where("order_no='%s'", $item['out_trade_no'])->find();
				if ($exist) {
					M("pay")->where("id=%d", $item['id'])->setField("uid", $exist['c_id']);
				} else {
					Log::record("交易号:{$item['out_trade_no']}");
				}
			}
		}
		
		
	}
	
	public function consume_log_fix() {
		$db   = M("consume_log");
		$data = $db->alias("l")->field("c.card_num,l.id")->join("left join ht_customer c on c.id=l.cid")->where("l.card_num is null")->limit(100)->select();
		$sql  = "";
		foreach ($data as $row) {
			$sql .= "update ht_consume_log set card_num='{$row['card_num']}' where id={$row['id']} ; \n";
		}
		Log::record($sql, Log::INFO);
		if ($sql) {
			M()->execute($sql);
		}
	}
	
	public function pay_consume_fix() {
		$data = M("pay")->where("(status=1 or status=2) and (param is not null or callback !='0') and out_trade_no not like 'CHARGE%'")
		                ->limit(500)->order("id")->select();
		$db   = M("consume_log");
		foreach ($data as $value) {
			$exist = $db->where("order_tag='%s'", $value['out_trade_no'])->find();
			//	dump($db->getLastSql());
			if ($exist) {
				continue;
			} else {
				if ($db->where("cTime >= %d and cTime <= %d and uid=%d and money=%d", $value['create_time'] - 3, $value['create_time'] + 3, $value['uid'], $value['total'])->find()) {
					continue;
				}
				$value['pay_type'] = '';
				if ($value['callback']) {
					if ($value['param']) {
						$value['pay_type'] .= '微信';
					} else {
						$value['pay_type'] .= '支付宝';
					}
				} else {
					continue;
				}
				$db->add(array (
					         'uid'       => $value['uid'],
					         'cid'       => $value['uid'],
					         'cTime'     => $value['create_time'],
					         'remark'    => '会员ID:' . $value['uid'] . ' 使用 ' . $value['pay_type'] . ' 消费了' . $value['total'],
					         'money'     => $value['total'],
					         'reason'    => '商城',
					         'type'      => '商城',
					         'pay_type'  => $value['pay_type'],
					         'order_tag' => $value['out_trade_no']
				         ));
				
			}
			
		}
		
	}
	
	public function order_auto_confirm() {
		$thirty_days_ago = NOW_TIME - 42 * 24 * 3600;//30工作日
		
		$orders = M("order")->where("status=2 and create_time<%d and ordertype=1", $thirty_days_ago)->select();
		M("order")->where("status=2 and create_time<%d and ordertype=1", $thirty_days_ago)->save(array (
			                                                                                         "status"         => 3,
			                                                                                         'is_auto_finish' => 1
		                                                                                         ));
		foreach ($orders as $order) {
			M("shoplist")->where("tag='{$order['tag']}'")->setField("status", "3");
		}
	}
}