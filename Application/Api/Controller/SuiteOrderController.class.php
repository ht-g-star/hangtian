<?php
namespace Api\Controller;

use Think\Controller;

class SuiteOrderController extends IndexController {
	protected $data;

	public function _initialize() {

		parent::_initialize();
		/* 读取数据库中的配置 */
		$config = S('DB_CONFIG_DATA');
		if (!$config) {
			$config = api('Config/lists');
			S('DB_CONFIG_DATA', $config);
		}
		C($config); //添加配置

	}


	public function get() {
		$card_num = $this->data['card_num'];
		if (!$card_num) {
			$this->ajaxReturn(array (
				                  "err" => 1,
				                  "msg" => "请求参数不存在!",
			                  ));
		}

		//分两种情况

		//1.网上预约过来的.可以查询自己订单的报告
		$pre    = C("DB_PREFIX");
		$orders = M("suite_order")->alias("o")
		                          ->join("{$pre}customer c on c.id=o.c_id")
		                          ->join("{$pre}suite s on s.id=o.s_id")
		                          ->join("{$pre}suite_period p on p.id=o.p_id")
		                          ->field("o.id as order_id,o.s_id,count as total,c.card_num,o.ctime,o.order_no,o.time,s.code,s.title,p.period")
		                          ->where("c.card_num='%s' and o.status=20", $card_num)
		                          ->order("o.id desc")
		                          ->limit(20)
		                          ->select();
		$result = array ();
		foreach ($orders as $order) {
			if ($order['time'] == 'am') {
				$time = "9:00";
			} else {
				$time = "15:00";
			}
			unset($order['time']);
			$order['order_date'] = strtotime($order['period'] . " " . $time);
			if ($order['order_date'] < time()) {
				continue;
			}
			$order['c_id'] = $order['code'];
			unset($order['s_id']);
			unset($order['period']);

			$order_info = M("suite_order_info")->field("id_num,realname,sex,tel,order_time as order_date")->where("o_id=%d", $order['order_id'])->select();

			if (empty($order_info)) {
				$order_info = array ();
			}

			foreach ($order_info as &$info) {
				if ($order['card_num']) {
					$info['card_num'] = $order['card_num'];
				} else {
					$customer = M("customer")->alias('m')->join("left join {$pre}company c on c.name=m.company")
					                         ->field("m.card_num,m.company,m.dept,m.position,m.is_onsite,m.realname,m.sex,m.id_num,m.mobile,m.rank,m.level,m.married,c.code as company_code")
					                         ->where(array (
						                                 'm.id_num' => $info['id_num'],
						                                 'm.status' => array ("EGT", 0)
					                                 ))->find();

					$info['card_num']     = $customer['card_num'];
					$info['rank']         = $customer['rank'];
					$info['level']        = $customer['level'];
					$info['married']      = $customer['married'];
					$info['company_code'] = $customer['company_code'];
				}
			}
			$order['order_info'] = $order_info;
			$result[]            = $order;
		}


		//2.没有订单,其他人下单的体检.看到自己的体检预约
		$id_num      = M("customer")->cache(true)->where("card_num='%s'", $card_num)->getField("id_num");
		$order_info2 = M("suite_order_info")->field("id,id_num,realname,sex,tel,source,order_time as order_date,s_id,o_id")
		                                    ->where("id_num='%s' and status>=10", $id_num)
		                                    ->order("id desc")
		                                    ->limit(30)->select();

		foreach ($order_info2 as &$info2) {
			$_suite = M("suite")->cache(true)->find($info2['s_id']);
			$order  = M("suite_order")->cache(true)->find($info2['o_id']);
			unset($info2['s_id']);
			unset($info2['o_id']);
			$result[] = array (
				'card_num'   => $card_num,
				'ctime'      => 0,
				'time'       => 0,
				'code'       => $_suite['code'],
				'title'      => $_suite['title'],
				'period'     => $info2['order_date'],
				'order_id'   => $order['id'],
				'total'      => 1,
				'cost'       => 0,
				'order_no'   => $order['order_no'],
				'order_info' => array ($info2),
			);

		}
		

		$this->ajaxReturn(array (
			                  "err"  => 0,
			                  "msg"  => "查询成功!",
			                  "data" => array (
				                  "order_lists" => $result
			                  )
		                  ));
	}
	

	/**
	 * POST 请求
	 * @param string $url
	 * @param array $param
	 * @param boolean $post_file 是否文件上传
	 * @return string content
	 */
	private function http_post($url, $param) {
		$oCurl = curl_init();
		if (stripos($url, "https://") !== false) {
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
		}
		$strPOST = json_encode($param);
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCurl, CURLOPT_POST, true);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
		$sContent = curl_exec($oCurl);
		$aStatus  = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200) {
			return $sContent;
		} else {
			return false;
		}
	}
}