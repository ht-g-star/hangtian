<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Wechat\Controller;
use Think\Log;
/**
 * 取消订单模型控制器
 * 文档模型列表和详情
 */
class CancelController extends FollowerController {

	/* 取消订单 */
	public function index() {
		$this->meta_title = '取消订单';
		if (IS_POST) {


			Log::write("取消订单请求：".json_encode($_POST));

			$id     = I('post.id', '', 'strip_tags');//获取orderid
			$id     = safe_replace($id);//过滤
			$order  = M("order");
			$status = $order->where("orderid='$id'")->getField("status");
			$shopid = $order->where("orderid='$id'")->getField("id");
			$data   = $order->where("id='$shopid'")->select();
			$cash   = 0;
			$count  = 0;
			$num    = 0;
			foreach ($data as $k => $val) {
				$goodid = $val['goodid'];
				$price  = get_good_price($goodid);
				/*取消的商品总额*/
				$cash += $val['num'] * $price;
				/*退货中的商品件数*/
				$num += $val['num'];
				/*退货中的商品种类数*/
				$count += 1;
			}
			$oorder = M('order')->where('orderid='.$_POST['id'])->find();
			Log::write("取消的订单：".json_encode($oorder));
			//订单已提交或未支付直接取消
			if ($status == -1 || $status == 1) {
				//设置订单取消
				//保存数据到取消表中后台调用
				unset($_POST['id']);
				$cancel = D("Admin/cancel");
				$cancel->create();
				$cancel->create_time = NOW_TIME;
				$cancel->status      = 3;
				$cancel->orderid     = $id;
				$cancel->cash        = $cash;//取消的金额
				$cancel->num         = $num;//取消的数量
				$cancel->count       = $count;//取消的种类
				$cancel->info        = "自助取消";

				$exist               = M("cancel")->where("orderid='%s'", $id)->find();

				if($oorder['ordertype'] == 2 || $oorder['ordertype'] == 3){
					if($status == 1 || $status == -1){
						$cha = time()-$oorder['create_time'];
						if($cha > 7200){
							$this->error('已距离建单时间超过2小时，不能取消该订单！');
						}else{
							$cdurl = "http://www.myvacation.cn/wuxi/api.php?Action=OrderCancel&order_id=".$oorder['tsorderid']."&cancel_msg=".I('post.reason')."&success=1";
							$cancelre=$this->getJsonByUrl($cdurl);

							Log::write("酒店/线路取消订单接口请求连接：".$cdurl);
							Log::write("酒店/线路取消订单接口数据：".json_encode($cancelre));

							if($cancelre['result']){
								if ($exist) {
									$cancel->id = $exist['id'];
									$cancel->status = 1;
									$cancel->save();
								} else {
									$cancel->add();
								}
								addUserLog('取消订单', is_login(), true);
								//设置订单为订单已取消
								$data = array ('status' => '6', 'backinfo' => '订单已关闭');
								//更新订单列表订单状态为已取消，清空取消订单操作
								if ($order->where("orderid='$id'")->setField($data)) {
									back_money($shopid);
									if($oorder['ordertype'] == 2){
										$this->success('申请成功，订单已取消', U("UserMallOrder/hotel"));
									}else{
										$this->success('申请成功，订单已取消', U("UserMallOrder/line"));
									}
								} else {
									$this->error('申请失败，请重试');
								}
							}else{
								$cancelmsg = explode('；', $cancelre['msg']);
								$cancelmsg = explode('）', $cancelmsg[0]);
								$this->error($cancelmsg[1]);
							}
						}
					}else{
						if ($exist) {
							$cancel->id = $exist['id'];
							$cancel->status = 1;
							$cancel->save();
						} else {
							$cancel->add();
						}
						addUserLog('取消订单', is_login(), true);
						//设置订单为订单已取消
						$data = array ('status' => '6', 'backinfo' => '订单已关闭');
						//更新订单列表订单状态为已取消，清空取消订单操作
						if ($order->where("orderid='$id'")->setField($data)) {
							back_money($shopid);
							if($_POST['type'] == 2){
								$this->success('申请成功，订单已取消', U("UserMallOrder/hotel"));
							}else{
								$this->success('申请成功，订单已取消', U("UserMallOrder/line"));
							}
						} else {
							$this->error('申请失败，请重试');
						}
					}
				}else{
					if ($exist) {
						$cancel->id = $exist['id'];
						$cancel->status = 1;
						$cancel->save();
					} else {
						$cancel->add();
					}
					addUserLog('取消订单', is_login(), true);
					//设置订单为订单已取消
					$data = array ('status' => '6', 'backinfo' => '订单已关闭');
					//更新订单列表订单状态为已取消，清空取消订单操作
					if ($order->where("orderid='$id'")->setField($data)) {
						back_money($shopid);
						$this->success('申请成功，订单已取消', U("UserMallOrder/index"));
					} else {
						$this->error('申请失败，请重试');
					}
				}
			} //订单已发货，或已支付未发货,需申请，申请状态码4，拒绝5，同意6
			else {
				unset($_POST['id']);
				$cancel = D("Admin/cancel");
				$cancel->create();
				$cancel->create_time = NOW_TIME;
				$cancel->status      = 1;
				$cancel->orderid     = $id;
				$cancel->cash        = $cash;//取消的金额
				$cancel->num         = $num;//取消的数量
				$cancel->count       = $count;//取消的种类
				$cancel->info        = "需要审核";

				$cancel->add();
				addUserLog('申请取消订单', is_login(), true);
				$data = array ('status' => '4');//设置订单状态为已提交，发货等状态不变
				if ($order->where("orderid='$id'")->setField($data)) {
					$this->success('申请成功', U("UserMallOrder/index"));
				} else {
					$this->error('申请失败，请重试');
				}
			}
		} else {
			$id     = I('get.id', '', 'strip_tags');//获取orderid
			$msg    = "申请取消订单:";
			$id     = safe_replace($id);//过滤
			$order  = M("order");
			$detail = $order->where("orderid='$id'")->select();
			$num    = $order->where("orderid='$id'")->getField("status");
			if ($num == "2") {
				$info = "当前提交的订单已发货,需审核通过后取消";
			}
			$list = M("shoplist");
			foreach ($detail as $n => $val) {
				$detail[ $n ]['id'] = $list->where('orderid=\'' . $val['id'] . '\'')->select();
			}
			$this->assign('info', $info);
			$this->assign('detaillist', $detail);
			$this->assign('id', $id);
			$this->assign('msg', $msg);
			$this->display();
			$this->meta_title = '取消订单';
		}
	}

	public function detail() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$orderid = I('get.id', '', 'strip_tags');//获取orderid
		$orderid = safe_replace($orderid);//过滤
		$cancel  = M("cancel");
		$list    = $cancel->where("orderid='$orderid'")->select();
		$info    = M("order")->where("orderid='$orderid'")->getField("backinfo");
		$this->assign('list', $list);
		$this->assign('backinfo', $info);
		$this->assign('id', $orderid);
		$msg              = "申请取消订单:";
		$this->meta_title = '取消订单详情';
		$this->assign('msg', $msg);
		$this->display();
	}
}
