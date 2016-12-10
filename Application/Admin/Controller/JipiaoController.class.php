<?php
namespace Admin\Controller;

use User\Api\UserApi as UserApi;

class JipiaoController extends AdminController {
	
	public function index() {
		if (isset($_GET['name'])) {
			$map['name'] = array ('like', '%' . (string)I('name') . '%');
		}
		$list = $this->lists('JipiaoChengshi', $map, 'id desc');
		$this->assign('list', $list);
		$this->meta_title = '城市管理';
		$this->display();
	}
	
	
	/**
	 * 添加视图
	 */
	public function add() {
		if ($_POST) {
			$Chengshi = D('JipiaoChengshi');
			if (false !== $Chengshi->update()) {
				$this->success('新增成功！', U('index'));
			} else {
				$error = $Chengshi->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$this->display('edit');
		}
	}
	
	
	/**
	 * 显示分类树，仅支持内部调
	 * @param  array $tree 分类树
	 *
	 */
	
	/* 编辑分类 */
	public function edit($id = null, $pid = 0) {
		$Area = D('JipiaoChengshi');
		
		if (IS_POST) { //提交表单
			if (false !== $Area->update($id)) {
				$this->success('编辑成功！', U('index'));
			} else {
				$error = $Area->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$info = $id ? $Area->info($id) : '';
			$this->assign('info', $info);
			$this->meta_title = '编辑地区';
			$this->display();
		}
	}
	
	
	
	
	/**
	 * 删除一个分类
	 *
	 */
	// public function del() {
	//     $cate_id = I('id');
	//     if (empty($cate_id)) {
	//         $this->error('参数错误!');
	//     }
	//     //删除该分类信息
	//     $res = M('JipiaoChengshi')->delete($cate_id);
	//     if ($res !== false) {
	//         //记录行为
	//         action_log('update_JipiaoChengshi', 'JipiaoChengshi', $cate_id, UID);
	//         $this->success('删除成功！');
	//     } else {
	//         $this->error('删除失败！');
	//     }
	// }
	
	//delete order
	public function delOrder() {
		$orderid = I("get.orderid");
		if (!empty($orderid)) {
			$map["orderid"]  = array ("EQ", $orderid);
			$map["isactive"] = array ("EQ", 1);
			$order           = M("jipiao_order")->where($map)->find();
			
			if (empty($order)) {
				$ret = array (
					"code" => -1,
					"msg"  => "没有找到相应订单，请核实。"
				);
				echo json_encode($ret);
				exit;
			} else {
				if ($order["orderstatus"] != "CANCELED") {
					$ret = array (
						"code" => -1,
						"msg"  => "该订单目前无法删除，请确认。"
					);
					echo json_encode($ret);
					exit;
				} else {
					$order["isactive"] = 0;
					M("jipiao_order")->save($order);
					$ret = array (
						"code" => 1,
						"msg"  => "订单删除成功"
					);
					echo json_encode($ret);
					exit;
				}
			}
		} else {
			$ret = array (
				"code" => -1,
				"msg"  => "订单号不能为空。"
			);
			echo json_encode($ret);
			exit;
		}
	}
	
	
	public function orderlist() {
		if (isset($_GET['lxr_name'])) {
			$map['lxr_name'] = array ('like', '%' . (string)I('lxr_name') . '%');
		}
		
		if (isset($_GET["orderstatus"])) {
			$map["orderstatus"] = array ("EQ", (string)I("orderstatus"));
		}
		
		if (isset($_GET["customercardnum"])) {
			$map['ht_customer.card_num'] = array ("like", '%' . (string)I('customercardnum') . '%');
		}
		
		if (isset($_GET["customerphone"])) {
			$map['ht_customer.mobile'] = array ("like", '%' . (string)I('customerphone') . '%');
		}
		
		if (isset($_GET["lxrphone"])) {
			$map['lxr_phone'] = array ("like", '%' . (string)I('lxrphone') . '%');
		}
		
		if (isset($_GET["begin_time"]) && isset($_GET["end_time"])) {
			$map["addtime"] = array (array ("egt", strtotime((string)I("begin_time"))), array ("elt", strtotime((string)I("end_time") . "+1 day")), "and");
		}
		
		if (isset($_GET["end_time"]) && !isset($_GET["begin_time"])) {
			$map["addtime"] = array ("elt", strtotime((string)I("end_time") . "+1 day"));
		}
		
		if (!isset($_GET["end_time"]) && isset($_GET["begin_time"])) {
			$map["addtime"] = array ("egt", strtotime((string)I("begin_time")));
		}
		
		$map["isactive"] = array ("EQ", 1);
		
		// print_r($map);
		
		$total = M("jipiao_order")->join(" ht_customer on ht_jipiao_order.uid = ht_customer.id")->where($map)->order("ht_jipiao_order.id desc")->field("ht_jipiao_order.*, ht_customer.mobile customermobile, ht_customer.realname customername, ht_customer.card_num customercardnum")->count();
		
		// $total            = count($list);
		$listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
		$REQUEST  = (array)I('request.');
		
		$page = new \Think\Page($total, $listRows, $REQUEST);
		if ($total > $listRows) {
			$page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		}
		$p = $page->show();
		$this->assign('_page', $p ? $p : '');
		
		$list = M("jipiao_order")->join(" ht_customer on ht_jipiao_order.uid = ht_customer.id")->where($map)->order("ht_jipiao_order.id desc")->field("ht_jipiao_order.*, ht_customer.mobile customermobile, ht_customer.realname customername, ht_customer.card_num customercardnum")->limit($page->firstRow . ',' . $page->listRows)->select();
		// print_r(json_encode($list));
		
		// $list = $this->lists('jipiao_order', $map, 'id desc');
		$this->assign('list', $list);
		$this->assign("total", $total);
		$this->meta_title = '订单管理';
		$this->display();
	}
	
	public function out() {
		if (isset($_GET['lxr_name'])) {
			$map['lxr_name'] = array ('like', '%' . (string)I('lxr_name') . '%');
		}
		
		if (isset($_GET["orderstatus"])) {
			$map["orderstatus"] = array ("EQ", (string)I("orderstatus"));
		}
		
		if (isset($_GET["customercardnum"])) {
			$map['ht_customer.card_num'] = array ("like", '%' . (string)I('customercardnum') . '%');
		}
		
		if (isset($_GET["customerphone"])) {
			$map['ht_customer.mobile'] = array ("like", '%' . (string)I('customerphone') . '%');
		}
		
		if (isset($_GET["lxrphone"])) {
			$map['lxr_phone'] = array ("like", '%' . (string)I('lxrphone') . '%');
		}
		
		if (isset($_GET["begin_time"]) && isset($_GET["end_time"])) {
			$map["addtime"] = array (array ("egt", strtotime((string)I("begin_time"))), array ("elt", strtotime((string)I("end_time") . "+1 day")), "and");
		}
		
		if (isset($_GET["end_time"]) && !isset($_GET["begin_time"])) {
			$map["addtime"] = array ("elt", strtotime((string)I("end_time") . "+1 day"));
		}
		
		if (!isset($_GET["end_time"]) && isset($_GET["begin_time"])) {
			$map["addtime"] = array ("egt", strtotime((string)I("begin_time")));
		}
		
		$map["isactive"] = array ("EQ", 1);
		$total           = M("jipiao_order")->join(" ht_customer on ht_jipiao_order.uid = ht_customer.id")->where($map)->order("ht_jipiao_order.id desc")->field("ht_jipiao_order.*, ht_customer.mobile customermobile, ht_customer.realname customername, ht_customer.card_num customercardnum")->count();
		$list            = M("jipiao_order")->join(" ht_customer on ht_jipiao_order.uid = ht_customer.id")->where($map)->order("ht_jipiao_order.id desc")->field("ht_jipiao_order.*, ht_customer.mobile customermobile, ht_customer.realname customername, ht_customer.card_num customercardnum")->select();
		foreach ($list as &$item) {
			$item['fromairport']   = setAirport($item['fromairport']);
			$item['toairport']     = setAirport($item['toairport']);
			$item['service_fee']   = ($item['fueltax'] + $item['faceprice'] + $item['airportfee']) * 0.03;
			$temp                  = explode("|", $item["passengername"]);
			$item['insurance_fee'] = count($temp) * 20;
			$item['total_fee']     = $item["totalorderprice"] + $item['insurance_fee'];
			$item['payamount']     = abs($item['payamount']);
			$item['refundamount']  = abs($item['refundamount']);
			switch ($item['orderstatus']) {
				case 'WAIT_PAY':
					$str = '待支付';
					break;
				case 'PAYING':
					$str = '支付中';
					break;
				case 'WAIT_AUDIT':
					$str = '待审核';
					break;
				case 'AUDIT_FALSE':
					$str = '审核失败';
					break;
				case 'CANCELED':
					$str = '已取消';
					break;
				case 'WAIT_ISSUE':
					$str = '待出票';
					break;
				case 'PAY_FALSE':
					$str = '支付失败';
					break;
				case 'ISSUE_FALSE':
					$str = '出票失败';
					break;
				case 'ISSUED':
					$str = '已出票';
					break;
				case 'ISSUED_SUSPEND':
					$str = '已出票（挂起）';
					break;
				case 'ENDORSE_WAIT_AUDIT':
					$str = '改签待审核';
					break;
				case 'ENDORSE_AUDIT_SUCCESS':
					$str = '改签审核成功';
					break;
				case 'ENDORSE_AUDIT_FALSE':
					$str = '改签审核失败';
					break;
				case 'WAIT_REFUND':
					$str = '待退票';
					break;
				case 'REFUND_FALSE':
					$str = '退票失败';
					break;
				case 'CANCEL_REFUND':
					$str = '取消退票';
					break;
				case 'REFUND_FINISH':
					$str = '退票成功';
					break;
				case 'REFUND_WAIT_AUDIT':
					$str = '退票待审核';
					break;
				case 'REFUND_AUDIT_FALSE':
					$str = '退票审核失败';
					break;
				case 'CANCEL_ENDORSE':
					$str = '取消改签';
					break;
				case 'WAIT_ENDORSE':
					$str = '待改签';
					break;
				case 'ENDORSE_FALSE':
					$str = '改签失败';
					break;
				case 'ENDORSE_FINISH':
					$str = '改签成功';
					break;
				case 'ENDORSE_REVIEW_PASS':
					$str = '改签后付款';
					break;
			}
			$item['orderstatus'] = $str;
			$item['addtime']     = date('Y-m-d H:i', $item['addtime']);
			
		}
		$xlsCell = array (
			array ('id', '订单ID'),
			array ('customercardnum', '会员ID'),
			array ('customername', '会员姓名'),
			array ('customermobile', '会员手机'),
			array ('orderid', '订单号'),
			array ('flightno', '航班号'),
			array ('departuredate', '出发日期'),
			array ('arrivaltime', '出发时间'),
			array ('arrivaldate', '达到日期'),
			array ('arrivaltime', '达到日期'),
			array ('fromairport', '出发机场'),
			array ('toairport', '到达机场'),
			array ('lxr_name', '联系人'),
			array ('lxr_phone', '联系人手机号'),
			array ('passengername', '乘机人'),
			array ('faceprice', '票面价格'),
			array ('airportfee', '机建价格'),
			array ('fueltax', '燃油费'),
			array ('service_fee', '服务费'),
			array ('insurance_fee', '保险'),
			array ('total_fee', '订单总价'),
			array ('payamount', '改签费用'),
			array ('refundamount', '退票费用'),
			array ('orderstatus', '订单状态'),
			array ('addtime', '下单时间'),
		
		);
		if (!$list) {
			$this->error('无数据');
		}
		$ordertype = I('ordertype') ? I('ordertype') : 1;
		$file      = '导出订单';
		switch ($ordertype) {
			case '1':
				$file = '导出订单';
				break;
			case '2':
				$file = '导出酒店订单';
				break;
			case '3':
				$file = '导出线路订单';
				break;
		}
		$this->exportExcel($file . date("YmdHis"), $xlsCell, $list, $total);
	}
	
	
	public function orderdetail() {
		$id     = I('id');
		$detail = M('jipiao_order')->where('id=' . $id)->find();
		// if($detail['orderstatus']=="PAYING"){
		//     $detail['orderstatus'] ='已支付';
		// }elseif($detail['orderstatus']=='WAIT_PAY'){
		//     $detail['orderstatus'] ='等待支付';
		// }else{
		//     $detail['orderstatus'] ='状态不明';
		// }
		$this->assign('detail', $detail);
		$this->meta_title = '订单管理';
		$this->display();
	}
	
}