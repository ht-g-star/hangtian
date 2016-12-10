<?php
/**
 * 粉丝
 * User: litao
 * Date: 15/12/19
 * Time: 09:51
 */

namespace Wechat\Controller;

use Org\Wechat\TPWechat;
use Org\Wechat\Wechat;
use Think\Controller;
use Think\Log;
use Think\Pay\Pay;

class PayController extends AuthController {
	
	
	public function index($orderid, $orderid1 = "") {
		vendor("WxpayAPI.lib.WxPay#Api");
		vendor("WxpayAPI.example.WxPay#JsApiPay");
		vendor('WxpayAPI.example.log');
		
		$suite_order = M("suite_order")->where("order_no='%s'", $orderid)->find();
		$order       = M("order")->where("orderid='%s'", $orderid)->find();
		$order1      = M("order")->where("orderid='%s'", $orderid1)->find();
		
		if (!$suite_order && !$order) {
			$this->error("订单不存在!");
		}
//初始化日志
		$logHandler = new \CLogFileHandler("../logs/" . date('Y-m-d') . '.log');
		$log        = \Log::Init($logHandler, 15);

//打印输出数组信息
		function printf_info($data) {
			foreach ($data as $key => $value) {
				echo "<font color='#00ff55;'>$key</font> : $value <br/>";
			}
		}


//①、获取用户openid
		$tools   = new \JsApiPay();
		$openId  = get_openid();
		$user    = session("user_auth");
		$user    = M("customer")->find($user['id']);
		$balance = $user['balance'];
		$this->assign("orderid", $orderid);
		if ($orderid1) {
			$this->assign("orderid1", $orderid1);
		}
//②、统一下单
		$input = new \WxPayUnifiedOrder();
		
		if ($suite_order) {
			$input->SetBody("航天疗养院-商品订单");
			$input->SetTotal_fee(intval($suite_order['cost'] * 100));
			$input->SetAttach($orderid);
			if ($balance >= $suite_order['cost']) {
				$this->assign("balance", $balance);
			}
//			$input->SetGoods_tag("test");
		} else {
			$input->SetBody("航天疗养院-体检预约订单");
			$input->SetTotal_fee(intval($order['total_money'] * 100));
			$input->SetAttach($order['tag']);
			if ($balance >= $order['total_money']) {
				$this->assign("balance", $balance);
			}
//			$input->SetGoods_tag("test");
		}
		$this->assign("balance", $balance);
//		$input->SetTotal_fee(intval(1));
		
		
		$total_money = $order["total_money"];
		if (!empty($order['payamount']) && $order['payamount'] >= 0) {
			$total_money = $order['payamount'];
		}
		
		if ($order1) {
			$total_money += $order1["total_money"];
		}
		
		$this->assign("total_money", $total_money);
		
		
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetOut_trade_no(\WxPayConfig::MCHID . date("YmdHis"));
		$notify_url = C('site_url') . "/Wechat/WechatNotify/index";
//		Log::write($notify_url);
		$input->SetNotify_url($notify_url);
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order_back = \WxPayApi::unifiedOrder($input);
//		echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//		printf_info($order);
		$jsApiParameters = $tools->GetJsApiParameters($order_back);
		$this->assign("jsApiParameters", $jsApiParameters);
//获取共享收货地址js函数参数
		$editAddress = $tools->GetEditAddressParameters();
		$this->assign("editAddress", $editAddress);
		
		$data   = array (
			'out_trade_no' => $input->GetAttach(),
			'money'        => floatval($input->GetTotal_fee() / 100),
			'total'        => floatval($input->GetTotal_fee() / 100),
			'status'       => 0,
			'create_time'  => time(),
			'update_time'  => time(),
			'type'         => \Think\Pay::TYPE_WECHAT
		);
		$db     = M("Pay");
		$exist  = $db->where("out_trade_no='%s'", $orderid)->find();
		$pay_id = 0;
		if ($exist) {
			$check  = $db->where("out_trade_no='%s'", $orderid)->save($data);
			$pay_id = $exist['id'];
		} else {
			$check  = $db->add($data);
			$pay_id = $check;
		}
		$this->assign("pay_id", $pay_id);

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
		/**
		 * 注意：
		 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
		 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
		 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
		 */
		$this->display();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
		/**
		 * 注意：
		 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
		 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
		 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
		 */
	}
	
	/**
	 * 余额支付
	 * @param $orderid
	 */
	public function balance() {
		$orderid  = empty($_POST["orderid"]) ? I("get.orderid") : $_POST["orderid"];
		$orderid1 = empty($_POST["orderid1"]) ? I("get.orderid1") : $_POST["orderid1"];
		$id       = safe_replace($orderid);//过滤
		$id1      = safe_replace($orderid1);//过滤
		
		$password   = I("post.password", "0", 'md5');
		$login_user = M("customer")->where("id=%d", is_login())->find();
		
		if ($login_user['password'] != $password) {
			$this->error("密码错误!");
		}
		
		$order = D("Home/Order");
		$order = $order->where("orderid='$id'")->find();
		if ($id1) {
			$order1 = M("order")->where(array ("orderid" => $id1))->find();
		}
		$balance = $login_user['balance'];
		
		$total_money = $order['total_money'];
		
		if (!empty($order['payamount']) && $order['payamount'] >= 0) {
			$total_money = $order['payamount'];
		}
		
		if ($order1) {
			$total_money += $order1["total_money"];
		}
		
		if ($balance < $total_money) {
			$this->error("余额不足!");
			return;
		}
		
		// $this->assign("total_money", $total_money);
		
		if ($order) {
			M()->startTrans();
			if ($total_money == 0) {
				$s1 = 1;
			} else {
				$s1 = M("customer")->where("id=" . $this->user['id'])->setDec("balance", $total_money);
			}
			
			if (!empty($order['payamount']) && $order['payamount'] >= 0) {
				$order['tag'] = $order['tag'] . "payamount";
				$data         = array (
					'out_trade_no' => $order['tag'],
					'money'        => $order['payamount'],
					'status'       => 1,
					'create_time'  => time(),
					'update_time'  => time(),
					'total'        => $order['payamount'],
					'uid'          => is_login(),
					'type'         => \Think\Pay::TYPE_BALANCE,
					'param'        => '',
				);
			} else {
				$data = array (
					'out_trade_no' => $order['tag'],
					'money'        => $order['total_money'],
					'status'       => 1,
					'create_time'  => time(),
					'update_time'  => time(),
					'total'        => $order['total_money'],
					'uid'          => is_login(),
					'type'         => \Think\Pay::TYPE_BALANCE,
					'param'        => '',
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
					'type'         => \Think\Pay::TYPE_BALANCE,
					'param'        => '',
				);
			}
			
			$db    = M("Pay");
			$exist = $db->where("out_trade_no='%s'", $order['tag'])->find();
			if ($order1) {
				$exist1 = $db->where("out_trade_no='%s'", $order1['tag'])->find();
			}
			
			if ($exist) {
				$s2 = $db->where("out_trade_no='%s'", $order['tag'])->save($data);
			} else {
				$s2 = $db->add($data);
			}
			
			if ($order1) {
				if ($exist1) {
					$s2 = $db->where("out_trade_no='%s'", $order1['tag'])->save($data1);
				} else {
					$s2 = $db->add($data1);
				}
			}
			
			
			$s3 = M("order")->where("id='%d'", $order['id'])->save(array (
				                                                       'is_pay'      => 2,
				                                                       'status'      => 1,
				                                                       'update_time' => time()
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
				"money"    => $total_money,
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
				$Filght  = new \Api\Controller\FlightController();
				$Insu    = new \Api\Controller\InsuController();
				$jpitems = M('jipiao_order')->where(array ('orderid' => $order['orderid'], "isactive" => 1))->find();
				
				$ck = explode("|", $jpitems["passengername"]);
				$ck = count($ck);
				
				if ($order1) {
					$jpitems1 = M('jipiao_order')->where(array ('orderid' => $order1['orderid'], "isactive" => 1))->find();
				}
				
				if ($jpitems) {
					if ($jpitems["orderstatus"] == "ENDORSE_REVIEW_PASS") {
						$res = $Filght->bpPay(array ('orderID' => $jpitems['externalorderid'], 'externalOrderID' => $jpitems['orderid']));
						if ($res['responseMetaInfo']['resultCode'] == '0') {
							$jpitems["orderstatus"] = "ENDORSE_WAIT_AUDIT";
							M("jipiao_order")->save($jpitems);
							$insuItem = M("insure_order")->where(array ("oid" => $order['orderid']))->find();
							
							if (!empty($insuItem["newoid"])) {
								$resInsu = $Insu->payOrder($insuItem["newoid"], $insuItem["extenalorderid"], "", "");
								
								$insuItem["status"]    = 4;
								$insuItem["insureid"]  = $resInsu["underWriteInfos"][0]["insureId"];
								$insuItem["paystatus"] = "SUCCESS";
								M("insure_order")->save($insuItem);
								
								// M("Customer")->where(array("id" => $jpitems["uid"]))->setInc("balance", 20 * $ck);
								//   						Log::write("改签的时候，退还". $ck * 20 ."元保险费用。但是要在支付完成后，再退还。");
							}
							M()->commit();
							Log::write("改签差价支付成功：" . json_encode($jpitems));
							parent::success("支付成功!", U('UserMallOrder/jipiao'));
						} else {
							M()->rollback();
							Log::write("支付出错,结果:" . $res['responseMetaInfo']['reason']);
							$this->error("支付出错,请重试:" . $res['responseMetaInfo']['reason']);
						}
					} else {
						//调用
						
						$res = $Filght->bpPay(array ('orderID' => $jpitems['externalorderid'], 'externalOrderID' => $jpitems['orderid']));
						if ($res['responseMetaInfo']['resultCode'] == '0') {
							M('jipiao_order')->where(array ('orderid' => $jpitems['orderid'], "isactive" => 1))->setField('orderstatus', 'PAYING');
							$insuItem = M("insure_order")->where(array ("oid" => $order['orderid']))->find();
							
							$resInsu = $Insu->payOrder($insuItem["oid"], $insuItem["extenalorderid"], "", "");
							
							$insuItem["status"]    = 4;
							$insuItem["insureid"]  = $resInsu["underWriteInfos"][0]["insureId"];
							$insuItem["paystatus"] = "SUCCESS";
							M("insure_order")->save($insuItem);
							if (!$order1) {
								M()->commit();
								parent::success("支付成功!", U('UserMallOrder/jipiao'));
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
						
						$insuItem1["status"]    = 4;
						$insuItem1["insureid"]  = $resInsu1["underWriteInfos"][0]["insureId"];
						$insuItem1["paystatus"] = "SUCCESS";
						M("insure_order")->save($insuItem1);
						M()->commit();
						parent::success("支付成功!", U('UserMallOrder/jipiao'));
					} else {
						M()->rollback();
						Log::write("支付出错,结果:" . $res['responseMetaInfo']['reason']);
						$this->error("支付出错,请重试:" . $res['responseMetaInfo']['reason']);
					}
				}
				
				$this->sendMail($order['orderid']);
				add_score($order['uid'], $order['tag'], $total_money);
				if (!$jpitems) {
					parent::success("支付成功!", U('Wechat/UserMallOrder/detail', array ('id' => $order['orderid'])));
				}
			} else {
				M()->rollback();
				Log::write("支付出错,结果: $s1 && $s2 && $s3 && $s4");
				$this->error("出错,请重试!");
			}
		} else {
			$order = M("suite_order")->where("order_no='%s'", $orderid)->find();
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
				'type'         => \Think\Pay::TYPE_BALANCE,
				'param'        => '',
			);
			
			$db    = M("Pay");
			$exist = $db->where("out_trade_no='%s'", $order['order_no'])->find();
			if ($exist) {
				$s2 = $db->where("out_trade_no='%s'", $order['order_no'])->save($data);
			} else {
				$s2 = $db->add($data);
			}
			$s3         = M("suite_order")->where("order_no='%s'", $orderid)->save(array (
				                                                                       'status'      => 10,
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
				'pay_type' => '余额',
				"money"    => $order['total_money'],
				'remark'   => "会员{$login_user['realname']}预约体检消费了{$order['cost']},订单号:{$order['order_no']}"
			)));
			if ($s1 && $s2 && $s3 && $s4) {
				M()->commit();
				R("Api/SuiteOrderSend/send", array ('order_id' => $order['id']));
				
				$this->sendSuiteMail($order['id']);
				add_score($order['c_id'], $order['order_no'], $order['cost']);
				
				parent::success("支付成功!", U('User/index'));
			} else {
				M()->rollback();
				$this->error("出错,请重试!");
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
		$content = '收到了新的体检预约，编号:' . $orderid;
		if (C('MAIL_PASSWORD')) {
			SendMail(C('MAIL_RECEIVER'), $title, $content);
		}
	}
	
	
	public function charge() {
		$this->assign("meta_title", "充值");
		if (IS_POST) {
			vendor("WxpayAPI.lib.WxPay#Api");
			vendor("WxpayAPI.example.WxPay#JsApiPay");
			vendor('WxpayAPI.example.log');

//初始化日志
			$logHandler = new \CLogFileHandler("../logs/" . date('Y-m-d') . '.log');
			$log        = \Log::Init($logHandler, 15);

//打印输出数组信息
			function printf_info($data) {
				foreach ($data as $key => $value) {
					echo "<font color='#00ff55;'>$key</font> : $value <br/>";
				}
			}


//①、获取用户openid
			$tools    = new \JsApiPay();
			$openId   = get_openid();
			$order_id = $this->createOrderNo();
			$user     = session("user_auth");
			$user     = M("customer")->find($user['id']);
			$this->assign("orderid", $order_id);
//②、统一下单
			$input = new \WxPayUnifiedOrder();
			
			$input->SetBody("充值订单-航天疗养院");
			$input->SetTotal_fee(intval(I('post.money') * 100));
			$input->SetAttach($order_id);
			
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetOut_trade_no(\WxPayConfig::MCHID . date("YmdHis"));
			$notify_url = C('site_url') . "/Wechat/WechatNotify/index";
//		Log::write($notify_url);
			$input->SetNotify_url($notify_url);
			$input->SetTrade_type("JSAPI");
			$input->SetOpenid($openId);
			$order_back = \WxPayApi::unifiedOrder($input);
//		echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//		printf_info($order);
			$jsApiParameters = $tools->GetJsApiParameters($order_back);
			$this->assign("jsApiParameters", $jsApiParameters);
//获取共享收货地址js函数参数
			$editAddress = $tools->GetEditAddressParameters();
			$this->assign("editAddress", $editAddress);
			
			$data   = array (
				'out_trade_no' => $input->GetAttach(),
				'money'        => floatval($input->GetTotal_fee() / 100),
				'status'       => 0,
				'create_time'  => time(),
				'update_time'  => time(),
				'uid'          => $user['id'],
				'type'         => \Think\Pay::TYPE_WECHAT
			);
			$db     = M("Pay");
			$exist  = $db->where("out_trade_no='%s'", $order_id)->find();
			$pay_id = 0;
			if ($exist) {
				$check  = $db->where("out_trade_no='%s'", $order_id)->save($data);
				$pay_id = $exist['id'];
			} else {
				$check  = $db->add($data);
				$pay_id = $check;
			}
			$this->assign("pay_id", $pay_id);

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
			/**
			 * 注意：
			 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
			 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
			 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
			 */
			$this->display('charge_pay');

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
			/**
			 * 注意：
			 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
			 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
			 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
			 */
		} else {
			$this->display();
		}
	}

// 生成支付订单号
	public function createOrderNo() {
		if (is_login()) {
			$uid  = is_login();
			$code = "CHARGE" . date('Ym', time()) . substr(time(), 4) . $uid;
			return $code;
		}
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