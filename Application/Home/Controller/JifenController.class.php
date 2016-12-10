<?php
namespace Home\Controller;

use Think\Controller;
use Think\Log;
use Common\Api\GatApi;
use Vendor\Sms\Sms;

class JifenController extends HomeController {

	const JIFEN_CHANGE_REASON = '兑换关爱积分';
	const JIFEN_APPID = '20091123'; // 正式

	const JIFEN_ORDER_STATUS_ORDER = 1;//下单扣款完成
	const JIFEN_ORDER_STATUS_CHANGEING = 2;//兑换中
	const JIFEN_ORDER_STATUS_CHANGE_SUCCESS = 3;//兑换完成

	/* 用于账户余额兑换关爱积分 */
	public function change() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		if(IS_POST) {
			$paymoney = I('post.money');//填写的支付金额
			//验证支付金额
			$paymoney = (float)sprintf('%.2f', $paymoney);
			if($paymoney <= 0) {
				$this->error('金额填写须大于零，且保留小数点后两位', U('/Home/Change/change'));
			}

			$user = session("user_auth");
			$customerInfo = M("customer")->find($user['id']);
			if($customerInfo['balance'] < $paymoney) { 
				$this->error('余额不足', U('/Home/Change/change'));
			} else {
				$ordersn  = self::JIFEN_APPID.date('Ym',time()).time().$user['id'];
				$money    = $paymoney;
				$reason   = self::JIFEN_CHANGE_REASON;
				$corp_id  = $customerInfo['id']; //工号
				$mobile   = $customerInfo['mobile'];

				$data['tradeno']     = $ordersn;
				$data['uid']         = $user['id'];
				$data['money']       = $money;
				$data['status']      = self::JIFEN_ORDER_STATUS_ORDER;//下单扣款状态
				$data['create_time'] = time();
				$data['update_time'] = time();

				M()->startTrans();
				$jifen_id = M('jifen_exchange')->fetchSql(false)->add($data);
				//更新customer,扣除余额
				$balance = $customerInfo['balance'] - $paymoney;
				$customer_id = M('customer')->fetchSql(false)->save(array (
					'id'                => $user['id'],
					'balance'           => $balance,              //余额
					'last_expense_time' => time(),         //最后一次消费时间
					'service'           => '兑换关爱积分', //服务项目
					));

				$consume_id = M('consume_log')->fetchSql(false)->add(array (
					'uid'    => $user['id'],
					'cid'    => $user['id'],
					'cTime'  => time(),
					'money'  => $paymoney,
					'reason' => '关爱积分兑换',
					'type'   => '商城',
					'remark' => '会员'.$user['realname'].'兑换关爱积分消费了'.$paymoney.',交易号:'.$ordersn,
					));

				$sms_content = '您的账户在'.date('Y-m-d H:i:s').'兑换关爱积分消费了'.$paymoney.',现在的余额为'.$balance.'。';

				if($jifen_id && $customer_id && $consume_id) {
					M()->commit();
					//短信通知
					$sms = new Sms();
					$send_status = $sms->send($user['mobile'], $sms_content);
					Log::write($send_status);
					//调接口
					$assign_result = GatApi::jifen_assign($ordersn, $money, $reason, $corp_id, $mobile);
					M('jifen_exchange')->save(array(
						'id'     => $jifen_id,
						'status' => self::JIFEN_ORDER_STATUS_CHANGEING,
						)
					);
					//更新
					if($assign_result['error_code'] == '0') {
						unset($data);
						$data['id']          = $jifen_id;
						$data['status']      = self::JIFEN_ORDER_STATUS_CHANGE_SUCCESS;
						$data['update_time'] = time();
						M('jifen_exchange')->save($data);
					}
				}else{
					M()->rollback();
				}
				$this->success('积分兑换成功', U('/Home/Center/index'));
			}
		}else {
			$this->redirect('/Change/change');
		}
	}

	public function order() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();
		$count = M('jifen_exchange')->where('uid='.$uid)->count();
		$page = new \Think\Page($count, 5);
		$show = $page->show();
		$list = M('jifen_exchange')->order('create_time desc')->where('uid='.$uid)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->assign('uid', session("user_auth"));
		$this->meta_title = '关爱积分兑换记录'; 
		$this->display();
	}
}
