<?php
namespace Home\Controller;

use Admin\Model\ChargeReasonModel;
use Home\Controller\SuiteController;
use Think\Controller;
use Think\Log;
use Think\Model;
use Think\Pay;

/**
 * 支付模型控制器
 * 文档模型列表和详情
 */
class PayController extends HomeController {
	
	public function index() {
		
		$exist_order = M("order")->where("orderid='%s'", I("orderid"))->find();
		if ($exist_order && $exist_order['status'] >= 1 && $exist_order["ordertype"] != 4) {
			$this->error("已经支付!", U('Home/Center/allorder'));
		}
		
		
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		} else {
			$user = session('user_auth');
			$this->assign("balance", M("customer")->where("id=%d", $user['id'])->getField("balance"));
		}
		if (IS_POST) {
			//页面上通过表单选择在线支付类型，支付宝为alipay 财付通为tenpay
			/* 支付设置 */
			$payment = array (
				'tenpay'   => array (
					// 加密key，开通财付通账户后给予
					'key'     => C('TENPAYKEY'),
					// 合作者ID，财付通有该配置，开通财付通账户后给予
					'partner' => C('TENPAYPARTNER')
				),
				'alipay'   => array (
					// 收款账号邮箱
					'email'   => C('ALIPAYEMAIL'),
					// 加密key，开通支付宝账户后给予
					'key'     => C('ALIPAYKEY'),
					// 合作者ID，支付宝有该配置，开通易宝账户后给予
					'partner' => C('ALIPAYPARTNER')
				),
				'palpay'   => array (
					'business' => C('PALPAYPARTNER')
				),
				'yeepay'   => array (
					'key'     => C('YEEPAYPARTNER'),
					'partner' => C('YEEPAYKEY')
				),
				'kuaiqian' => array (
					'key'     => C('KUAIQIANPARTNER'),
					'partner' => C('KUAIQIANKEY')
				),
				'unionpay' => array (
					'key'     => C('UNIONPARTNER'),
					'partner' => C('UNIONKEY')
				)
			);
			$paytype = I('post.paytype');
			$paytype = safe_replace($paytype);//过滤
			
			$pay_info = session("pay_info");

//			if (empty($_POST['orderid']) || $_POST['orderid'] != $pay_info['order_no']) {
//				$this->error("订单信息错误!");
//			}
			if (empty($_POST['orderid'])) {
				$this->error("订单信息错误!");
			}
			
			$order_no = safe_replace($order_no);//过滤
			
			$body  = $pay_info['body'];//商品描述
			$title = $pay_info['title'];//设置商品名称
			$money = $pay_info['money'];
			$pay   = new \Think\Pay($paytype, $payment[ $paytype ]);
			$vo    = new \Think\Pay\PayVo();
			if (empty($pay_info['out_trade_no'])) {
				$pay_info['out_trade_no'] = $pay_info['order_no'];
			}
			if ($_SERVER['HTTP_HOST'] == "ht738.cn") {
				$money = 0.01;
			}
			$vo->setBody($body)
			   ->setFee($money)//支付金额
			   ->setOrderNo($pay_info['out_trade_no'])//订单号
			   ->setTitle($title)//设置商品名称
			   ->setCallback("Home/Pay/success")/*** 设置支付完成后的后续操作接口 */
			   ->setUrl(U("Home/Pay/over"))/* 设置支付完成后的跳转地址*/
			   ->setParam(array ('order_id' => $order_no));
			echo $pay->buildRequestForm($vo);
			//Log::write("支付宝build:" . print_r($pay->buildRequestForm($vo), true));
		} else {
			
			/* uid调用*/
			$uid = is_login();
//			$score = get_score($uid);
			
			$this->meta_title = '支付订单';
			
			$so_id    = I("get.so_id");
			$orderid1 = i("get.orderid1");
			if ($so_id) {//体检套餐
				$result = M("suite_order")->where("id=%d and c_id=%d", $so_id, $uid)->find();
				
				if (!$result) {
					$this->error("未找到您的订单!");
				}
				$title = M("suite")->where("id=%d", $result['s_id'])->getField("title");
				session("pay_info", array (
					"money"    => $result['cost'],
					"body"     => C('WEB_SITE_TITLE') . "-预约体检套餐-" . $title,
					"title"    => $title,
					"order_no" => $result['order_no']
				));
				$this->assign("codeid", $result['order_no']);
				
			} else if ($orderid1) {
				$order    = D("order");
				$id       = I("orderid");
				$id       = safe_replace($id);//过滤
				$orderid1 = safe_replace($orderid1);
				$this->assign('codeid', $id);
				$this->assign("orderid1", $orderid1);
				
				$total  = $order->where("orderid='$id'")->getField('total_money');
				$total1 = 0;
				if ($orderid1) {
					$total1 = $order->where("orderid='$orderid1'")->getField('total_money');
				}
				
				session("pay_info", array (
					"money"     => $total + $total1,
					"body"      => C('WEB_SITE_TITLE') . "-商品订单",
					"title"     => C('WEB_SITE_TITLE') . "-商品订单",
					"order_no"  => $id,
					"order_no1" => $orderid1
				));
				$this->assign('goodprice', $total + $total1);
				
			} else {
				//在此之前goods1的业务订单已经生成，状态为等待支付
				$id    = I("orderid");
				$id    = safe_replace($id);//过滤
				$order = D("order");
				$this->assign('codeid', $id);
				
				$total     = $order->where("orderid='$id'")->getField('total_money');
				$payamount = $order->where("orderid='$id'")->getField('payamount');
				if (!empty($payamount) && $payamount >= 0) {
					$total = $payamount;
				}
				
				session("pay_info", array (
					"money"    => $total,
					"body"     => C('WEB_SITE_TITLE') . "-商品订单",
					"title"    => C('WEB_SITE_TITLE') . "-商品订单",
					"order_no" => $id
				));
				$this->assign('goodprice', $total);
			}
			
			$order = M("order")->where("orderid='$id'")->find();
			$this->assign("ordertype", $order['ordertype']);
			$this->display();
			
		}
	}
	
	public function success($money, $param) {
		if (session("pay_verify") == true) {
			session("pay_verify", null);
			
			//处理订单
			$data = array ('status' => '1', 'ispay' => '1');//订单已经支付,状态为已提交
			Log::write("支付宝参数:" . print_r($param, true));
			$is_order = M('order')->where(" tag='%s' or orderid='%s'", $param['order_id'], $param['order_id'])->setField($data);
			M('pay')->where(array ('out_trade_no' => $param['order_id']))->setField(array (
				                                                                        "status"      => 1,
				                                                                        'update_time' => time()
			                                                                        ));
			M("suite_order")->where("order_no='%s'", $param['order_id'])->save(array (
				                                                                   'status'      => SuiteController::ORDER_STATUS_OK,
				                                                                   'update_time' => time()
			                                                                   ));
			
			
			// 发送邮件
//			$uid     = M("pay")->where(array ('out_trade_no' => $param['order_id']))->getField('uid');
//			$mail    = get_email($uid);//获取会员邮箱
//			$title   = "订单提醒";
//			$content = '收到了新的订单，订单编号:' . ;;
//			if (C('MAIL_PASSWORD')) {
//				SendMail(C('MAIL_RECEIVER'), $title, $content);
//			}
			if ($is_order) {
				$this->sendMail($param['order_id']);
				//增加积分
				$order = M('order')->where(" tag='%s' or orderid='%s'", $param['order_id'], $param['order_id'])->find();
				add_score($order['uid'], $order['tag'], $order['total_money']);
			} else {
				$this->sendSuiteMail($param['order_id']);
				$suite_order = M("suite_order")->where("order_no='%s'", $param['order_id'])->find();
				add_score($suite_order['c_id'], $suite_order['order_no'], $suite_order['cost']);
			}
			
			
		} else {
			E("Access Denied");
		}
	}
	
	public function over() {
		$this->assign("meta_title", '支付成功');
		$this->display('success');
	}
	
	
	/**
	 * 余额支付
	 * @param $orderid
	 */
	public function balance() {
		$orderid  = I('post.orderid');
		$orderid1 = I("post.orderid1");
		$id       = safe_replace($orderid);//过滤
		$id1      = safe_replace($orderid1);
		
		$password   = I("post.password", "0", 'md5');
		$user       = session('user_auth');
		$login_user = M("customer")->where("id=%d", $user['id'])->find();
		if ($login_user['password'] != $password) {
			$this->error("密码错误!");
		}
		
		$order       = D("order");
		$order       = $order->where("orderid='$id' or tag='$id'")->find();
		$balance     = $login_user['balance'];
		$total_order = $order['total_money'];
		
		if ($order["payamount"] >= 0 && !empty($order["payamount"])) {
			$total_order = $order["payamount"];
		}
		
		
		if (!empty($id1)) {
			$order1      = M("order")->where("orderid='$id1' or tag='$id1'")->find();
			$total_order = $total_order + $order1['total_money'];
		}
		
		if ($balance < $total_order) {
			$this->error("余额不足!");
			return;
		}
		
		if ($order) {
			M()->startTrans();
			if ($total_order == 0) {
				$s1 = 1;
			} else {
				$s1 = M("customer")->where("id=%d", $user['id'])->setDec("balance", $total_order);
			}
			
			
			if (empty($order["payamount"])) {
				$data = array (
					'out_trade_no' => $order['tag'],
					'money'        => $order['total_money'],
					'status'       => 1,
					'create_time'  => time(),
					'update_time'  => time(),
					'total'        => $order['total_money'],
					'uid'          => is_login(),
				);
			} else {
				$order['tag'] = $order['tag'] . "payamount";
				$data         = array (
					'out_trade_no' => $order['tag'],
					'money'        => $order['payamount'],
					'status'       => 1,
					'create_time'  => time(),
					'update_time'  => time(),
					'total'        => $order['payamount'],
					'uid'          => is_login(),
				);
			}
			
			if ($order1) {
				$data1 = array (
					'out_trade_no' => $order1['tag'],
					'money'        => $order1['total_money'],
					'status'       => 1,
					'create_time'  => time(),
					'update_time'  => time(),
					'total'        => $order1['total_money'],
					'uid'          => is_login(),
				);
			}
			
			$db = M("Pay");
			Log::write("测试数据tag：" . $order['tag']);
			$exist = $db->where("out_trade_no='%s'", $order['tag'])->find();
			if ($order1) {
				$exist1 = $db->where("out_trade_no='%s'", $order1['tag'])->find();
			}
			// if ($exist && $exist['status'] == 1) {
			// 	Log::write("已经支付!");
			// 	M()->rollback();
			// 	parent::success("支付失败,请重试!", U('SuiteOrder/allorder'));
			// 	exit;
			// }	
			Log::write("测试数据." . json_encode($exist));
			Log::write("测试数据." . json_encode($data));
			
			$pay_data = array (
				'out_trade_no' => $order['tag'],
				'money'        => $order['total_money'],
				'status'       => 1,
				'create_time'  => time(),
				'update_time'  => time(),
				'total'        => $order['total_money'],
				'uid'          => is_login(),
				'type'         => Pay::TYPE_BALANCE,
				'url'          => '',
				'callback'     => '',
				'param'        => ''
			);
			if ($exist) {
				$s2 = $db->where("out_trade_no='%s'", $order['tag'])->save($pay_data);
			} else {
				Log::write("Tag......");
				$s2 = $db->add($pay_data);
			}
			
			if ($order1) {
				if ($exist1) {
					$s2 = $db->where("out_trade_no='%s'", $order1['tag'])->save($data1);
				} else {
					Log::write("Tag1......");
					$s2 = $db->add($data1);
				}
				
			}
			
			$s3 = M("order")->where("id='%d'", $order['id'])->save(array (
				                                                       'is_pay'      => 2,
				                                                       'status'      => 1,
				                                                       'update_time' => NOW_TIME
			                                                       ));
			if ($order1) {
				$s3 = M("order")->where("id='%d'", $order1['id'])->save(array (
					                                                        'is_pay'      => 2,
					                                                        'status'      => 1,
					                                                        'update_time' => NOW_TIME
				                                                        ));
			}
			$login_user = session('user_auth');
			$s4         = M("consume_log")->add((array (
				'uid'      => is_login(),
				'cid'      => is_login(),
				'cTime'    => time(),
				'card_num' => $login_user['card_num'],
				'reason'   => "购物",
				'type'     => "商城",
				"money"    => $order['total_money'],
				'pay_type' => '余额',
				'remark'   => "会员{$login_user['realname']}购物消费了{$order['total_money']},订单号:{$order['orderid']}"
			)));
			
			if ($order1) {
				$s4 = M("consume_log")->add((array (
					'uid'      => is_login(),
					'cid'      => is_login(),
					'cTime'    => time(),
					'card_num' => $login_user['card_num'],
					'reason'   => "购物",
					'type'     => "商城",
					"money"    => $order1['total_money'],
					'pay_type' => '余额',
					'remark'   => "会员{$login_user['realname']}购物消费了{$order1['total_money']},订单号:{$order1['orderid']}"
				)));
			}
			if ($s1 && $s2 && $s3 && $s4) {
				
				if ($order["ordertype"] == 1) {
					M()->commit();
				}
				
				if ($order["ordertype"] == 2 || $order["ordertype"] == 3) {
					M()->commit();
					$this->ts($order["orderid"]);
				}
				//如果是机票订单 修改订单状态
				$jpitems = M('jipiao_order')->where(array ('orderid' => $order['orderid'], "isactive" => 1))->find();
				
				$ck = explode("|", $jpitems["passengername"]);
				$ck = count($ck);
				
				if ($order1) {
					$jpitems1 = M('jipiao_order')->where(array ('orderid' => $order1['orderid'], "isactive" => 1))->find();
				}
				
				if ($jpitems) {
					//调用
					$Insu   = new \Api\Controller\InsuController();
					$Filght = new \Api\Controller\FlightController();
					
					if ($jpitems["orderstatus"] == "ENDORSE_REVIEW_PASS") {
						$jpitems["orderstatus"] = "ENDORSE_WAIT_AUDIT";
						M("jipiao_order")->save($jpitems);
						Log::write("改签差价支付成功：" . json_encode($jpitems));
						$res = $Filght->bpPay(array ('orderID' => $jpitems['externalorderid'], 'externalOrderID' => $jpitems['orderid']));
						if ($res['responseMetaInfo']['resultCode'] == '0') {
							$insuItem = M("insure_order")->where(array ("oid" => $order['orderid']))->find();
							
							if ($insuItem["newoid"]) {
								$resInsu = $Insu->payOrder($insuItem["newoid"], $insuItem["extenalorderid"], "", "");
								
								$insureid = "";
								for ($i = 0; $i < count($resInsu["underWriteInfos"]); $i++) {
									if ($i != 0) {
										$insureid .= "|";
									}
									$insureid .= $resInsu["underWriteInfos"][ $i ]["insureId"];
								}
								
								$insuItem["status"]    = 4;
								$insuItem["insureid"]  = $insureid;
								$insuItem["paystatus"] = "SUCCESS";
								M("insure_order")->save($insuItem);
								
								// M("Customer")->where(array("id" => $jpitems["uid"]))->setInc("balance", 20 * $ck);
								//   						Log::write("改签的时候，退还". $ck * 20 ."元保险费用。但是要在支付完成后，再退还。");
							}
							M()->commit();
							parent::success("支付成功!", U('Home/jipiao/orderShow', array ('oid' => $jpitems['orderid'])));
						} else {
							M()->rollback();
							Log::write("支付出错,结果:" . $res['responseMetaInfo']['reason']);
							$this->error("支付出错,请重试:" . $res['responseMetaInfo']['reason']);
						}
					} else {
						$res = $Filght->bpPay(array ('orderID' => $jpitems['externalorderid'], 'externalOrderID' => $jpitems['orderid']));
						if ($res['responseMetaInfo']['resultCode'] == '0') {
							M('jipiao_order')->where(array ('orderid' => $jpitems['orderid'], "isactive" => 1))->setField('orderstatus', 'PAYING');
							$insuItem = M("insure_order")->where(array ("oid" => $order['orderid']))->find();
							
							$resInsu = $Insu->payOrder($insuItem["oid"], $insuItem["extenalorderid"], "", "");
							
							$insureid = "";
							for ($i = 0; $i < count($resInsu["underWriteInfos"]); $i++) {
								if ($i != 0) {
									$insureid .= "|";
								}
								$insureid .= $resInsu["underWriteInfos"][ $i ]["insureId"];
							}
							
							$insuItem["status"]    = 4;
							$insuItem["insureid"]  = $insureid;
							$insuItem["paystatus"] = "SUCCESS";
							M("insure_order")->save($insuItem);
							
							if (!$order1) {
								M()->commit();
								parent::success("支付成功!", U('Home/jipiao/orderShow', array ('oid' => $jpitems['realid'])));
							}
						} else {
							M()->rollback();
							Log::write("支付出错,结果:" . $res['responseMetaInfo']['reason']);
							$this->error("支付出错,请重试:" . $res['responseMetaInfo']['reason']);
						}
					}
				}
				
				if ($jpitems1) {
					//调用
					$Insu   = new \Api\Controller\InsuController();
					$Filght = new \Api\Controller\FlightController();
					
					$res = $Filght->bpPay(array ('orderID' => $jpitems1['externalorderid'], 'externalOrderID' => $jpitems1['orderid']));
					if ($res['responseMetaInfo']['resultCode'] == '0') {
						M('jipiao_order')->where(array ('orderid' => $jpitems1['orderid'], "isactive" => 1))->setField('orderstatus', 'PAYING');
						$insuItem1 = M("insure_order")->where(array ("oid" => $order1['orderid']))->find();
						
						$resInsu1 = $Insu->payOrder($insuItem1["oid"], $insuItem1["extenalorderid"], "", "");
						
						$insureid1 = "";
						for ($i = 0; $i < count($resInsu1["underWriteInfos"]); $i++) {
							if ($i != 0) {
								$insureid1 .= "|";
							}
							$insureid1 .= $resInsu1["underWriteInfos"][ $i ]["insureId"];
						}
						
						$insuItem1["status"]    = 4;
						$insuItem1["insureid"]  = $insureid1;
						$insuItem1["paystatus"] = "SUCCESS";
						M("insure_order")->save($insuItem1);
						M()->commit();
						parent::success("支付成功!", U('Home/jipiao/orderShow', array ('oid' => $jpitems1['realid'])));
					} else {
						M()->rollback();
						Log::write("支付出错,结果:" . $res['responseMetaInfo']['reason']);
						$this->error("支付出错,请重试:" . $res['responseMetaInfo']['reason']);
					}
				}
				
				$this->sendMail($order['orderid']);
				add_score($order['uid'], $order['tag'], $order['total_money']);
				if (!$jpitems) {
					parent::success("支付成功!", U('Home/Order/detail', array ('id' => $order['orderid'])));
				}
			} else {
				M()->rollback();
				Log::write("支付出错,结果: $s1 && $s2 && $s3 && $s4");
				$this->error("出错,请重试!");
			}
		} else {
			$order = M("suite_order")->where("order_no='%s'", $orderid)->find();
			if ($order) {
				M()->startTrans();
				$s1 = M("customer")->where("id=%d", is_login())->setDec("balance", $order['cost']);
				
				$data = array (
					'out_trade_no' => $order['order_no'],
					'money'        => $order['cost'],
					'status'       => 1,
					'create_time'  => time(),
					'update_time'  => time(),
					'total'        => $order['cost'],
					'uid'          => is_login(),
				);
				
				$db    = M("Pay");
				$exist = $db->where("out_trade_no='%s'", $order['order_no'])->find();
				if ($exist) {
					$s2 = $db->where("out_trade_no='%s'", $order['order_no'])->save($data);
				} else {
					$s2 = $db->add($data);
				}
				$s3         = M("suite_order")->where("order_no='%s'", $orderid)->save(array (
					                                                                       'status'      => SuiteController::ORDER_STATUS_OK,
					                                                                       'update_time' => time()
				                                                                       ));
				$login_user = session('user_auth');
				$s4         = M("consume_log")->add((array (
					'uid'      => is_login(),
					'cid'      => is_login(),
					'cTime'    => time(),
					'card_num' => $login_user['card_num'],
					'reason'   => "预约体检",
					'type'     => "商城",
					"money"    => $order['total_money'],
					'pay_type' => '余额',
					'remark'   => "会员{$login_user['realname']}预约体检消费了{$order['cost']},订单号:{$order['order_no']}"
				)));
				if ($s1 && $s2 && $s3 && $s4) {
					M()->commit();
					R("Api/SuiteOrderSend/send", array ('order_id' => $order['id']));
					$this->sendSuiteMail($order['id']);
					add_score($order['c_id'], $order['order_no'], $order['cost']);
					parent::success("支付成功!", U('SuiteOrder/allorder'));
				} else {
					M()->rollback();
					$this->error("出错,请重试!");
				}
			} else {
				$this->error("订单不存在!");
			}
			
		}
		
		
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
		$content = '收到了新的体检预约，编号:' . M("suite_order")->where("id=%d", $orderid)->getField("order_no");
		if (C('MAIL_PASSWORD')) {
			SendMail(C('MAIL_RECEIVER'), $title, $content);
		}
	}
	
	public function refund() {
		echo substr("2016031458535504902", 16);
//		dump(wxrefund('4006162001201603214152277024',10,10));
//		$this->display();
		
	}
	
	
	public function chongzhi() {
		
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		if (IS_POST) {
			//页面上通过表单选择在线支付类型，支付宝为alipay 财付通为tenpay
			
			/* 支付设置 */
			$payment  = array (
				'tenpay'   => array (
					// 加密key，开通财付通账户后给予
					'key'     => C('TENPAYKEY'),
					// 合作者ID，财付通有该配置，开通财付通账户后给予
					'partner' => C('TENPAYPARTNER')
				),
				'alipay'   => array (
					// 收款账号邮箱
					'email'   => C('ALIPAYEMAIL'),
					// 加密key，开通支付宝账户后给予
					'key'     => C('ALIPAYKEY'),
					// 合作者ID，支付宝有该配置，开通易宝账户后给予
					'partner' => C('ALIPAYPARTNER')
				),
				'palpay'   => array (
					'business' => C('PALPAYPARTNER')
				),
				'yeepay'   => array (
					'key'     => C('YEEPAYPARTNER'),
					'partner' => C('YEEPAYKEY')
				),
				'kuaiqian' => array (
					'key'     => C('KUAIQIANPARTNER'),
					'partner' => C('KUAIQIANKEY')
				),
				'unionpay' => array (
					'key'     => C('UNIONPARTNER'),
					'partner' => C('UNIONKEY')
				)
			);
			$paytype  = I('post.paytype');
			$pay      = new \Think\Pay($paytype, $payment[ $paytype ]);
			$order_no = $this->createOrderNo(); //充值，生成订单号
			$body     = C('SITENAME') . "充值";//商品描述
			$title    = C('SITENAME') . "充值";//设置商品名称
			$money    = I('post.money');
			$money    = safe_replace($money);//过滤
			$vo       = new \Think\Pay\PayVo();
			$vo->setBody($body)
			   ->setFee($money)//支付金额
			   ->setOrderNo($order_no)//订单号
			   ->setTitle($title)//设置商品名称
			   ->setCallback("Home/Pay/successaccount")/*** 设置支付完成后的后续操作接口 */
			   ->setUrl(U("Home/Pay/over"))/* 设置支付完成后的跳转地址*/
			   ->setParam(array ('money' => $money, 'cid' => is_login()));
			echo $pay->buildRequestForm($vo);
		} else {
			
			$this->meta_title = '账号充值';
			//在此之前goods1的业务订单已经生成，状态为等待支付
			
			$this->display();
		}
	}
	
	// 生成支付订单号
	public function createOrderNo() {
		if (is_login()) {
			$uid  = is_login();
			$code = date('Ym', time()) . substr(time(), 4) . $uid;
			return $code;
		}
	}
	
	/**
	 * 充值支付成功
	 * @param type $money
	 * @param type $param
	 */
	public function successaccount($money, $param) {
		if (session("pay_verify") == true) {
			session("pay_verify", null);
			
			M()->startTrans();
			$s1 = M("charge_log")->add(array (
				                           'uid'    => 0,
				                           'cid'    => $param['cid'],
				                           'remark' => "用户通过支付宝自主充值" . $money,
				                           'cTime'  => time(),
				                           'money'  => $money,
				                           'reason' => 2
			                           ));
			$s2 = M("customer")->where("id=%d", $param['cid'])->setInc("balance", $money);
			if ($s1 && $s2) {
				M()->commit();
			} else {
				M()->rollback();
				Log::write("错误的充值!" . print_r($param, true));
			}
			$uid = is_login();
			// 发送邮件
//			M("member")->where("uid='$uid'")->setField('account', $param['money']);
//
//
//			$mail    = get_email($uid);//获取会员邮箱
//			$title   = "支付提醒";
//			$content = "您在<a href=\"" . C('DAMAIN') . "\" target='_blank'>" . C('SITENAME') . '</a>充值成功，交易号' . $param['order_id'];
//			if (C('MAIL_PASSWORD')) {
//				SendMail($mail, $title, $content);
//			}
//		} else {
//			E("Access Denied");
//		}
		}
	}
	
	/**
	 * Get请求获取JSON 转Array
	 * @param $url 请求地址(附带参数)
	 * @param return 数组对象
	 */
	protected function getJsonByUrl($url) {
		//缓存机制
//		$cache=M('travel_cache')->where(array('requesturl'=>$url))->find();
//		if($cache){
//			$result=$cache['result'];
//		}else{
		$result = file_get_contents($url);
		$result = iconv("GBK", "utf-8//IGNORE", $result);
		$result = json_decode($result, true);
		
		if ($result["result"] == false) {
			$this->auth_token(1);
			$result = file_get_contents($url);
			$result = iconv("GBK", "utf-8//IGNORE", $result);
			$result = json_decode($result, true);
			Log::write("测试数据5：" . json_encode($result));
			Log::write("测试数据5：" . json_encode($url));
		}


//			M('travel_cache')->add(array('requesturl'=>$url,'result'=>$result,'ctime'=>time()));
//		}
		return $result;
	}
	
	/**
	 * 线路和酒店订单推送
	 */
	private function ts($orderid) {
		$order = M('Order')->where(array ('orderid' => $orderid))->find();
		if ($order['ordertype'] != 1) {
			
			$url  = "http://www.myvacation.cn/wuxi/api.php?Action=OrderPayback" .
			        "&order_id=" . $order['tsorderid'] .
			        "&success=1";
			$zfre = $this->getJsonByUrl($url);
			Log::write("酒店/线路提交订单支付成功请求：" . $url);
			Log::write("酒店/线路提交订单支付成功返回数据：" . json_encode($zfre));
			if ($zfre['result'] == 1) {
				$ore           = M('Order')->where(array ('orderid' => $orderid))->find();
				$ore["ists"]   = 2;
				$ore["status"] = 2;
				M("Order")->save($ore);
			}
			//ists 1 代表订单推送   2 代表订单支付后推送
		}
	}
}
