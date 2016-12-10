<?php
namespace Api\Controller;

use Admin\Model\MemberModel;
use Think\Controller;
use Think\Log;

class SuiteOrderSendController extends Controller {
	protected $data;
	const HANLI_SEND_ORDER  = "http://127.0.0.1:8088/MeXamService.asmx/UpdateAppointment";
	const HANLI_SEND_ORDER2 = "http://127.0.0.1:8088/MeXamService.asmx/UpdateUser";

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


	public function send($order_id) {
		if (!$order_id) {
			Log::write("错误调用,无订单号!");
			return false;
		}

		/*
		 * {"order_id":"39","p_id":null,"total":"1","ctime":"1455585515","order_no":"2016021698525348","c_id":"000068","order_date":1450422000,"order_info":[{"id_num":"32028319900911614700008688","realname":"\u8463\u4e3d\u6167","sex":"0","tel":"13771125119","card_num":""}]}
*/

		$order      = M("suite_order")->field("id as order_id,count as total,ctime,order_no")
		                              ->where("id=%d", $order_id)
		                              ->find();
		$order_info = M("suite_order_info")->field("id_num,s_id,p_id,realname,sex,tel,order_time as order_date")->where("o_id=%d", $order['order_id'])->select();
		if (!$order_info) {
			return;
		}
		$pre = C("DB_PREFIX");

		foreach ($order_info as $info) {
			$customer = M("customer")->alias('m')->join("left join {$pre}company c on c.name=m.company")
			                         ->field("m.card_num,m.company,m.dept,m.position,m.is_onsite,m.realname,m.sex,m.id_num,m.mobile,m.rank,m.level,m.married,c.code as company_code")
			                         ->where(array (
				                                 'm.id_num' => $info['id_num'],
				                                 'm.status' => array ("EGT", 0)
			                                 ))->find();

			$card_num = $customer['card_num'];

			$info['rank']         = $customer['rank'];
			$info['level']        = $customer['level'];
			$info['married']      = $customer['married'];
			$info['company_code'] = $customer['company_code'];

			$order['card_num'] = $card_num;
			$suite             = M("suite")->find($info['s_id']);
			if (!$suite) {
				continue;
			}
			$order['code']  = $suite['code'];
			$order['title'] = $suite['title'];

			$info['card_num'] = $card_num ? $card_num : $order['card_num'];
			$order['c_id']    = $suite['code'];
			$period           = M("suite_period")->find($info['p_id']);
			if (!$period) {
				return;
			}
			$order['period']     = $period['period'];
			$order['order_date'] = $info['order_date'];
			$order['order_info'] = array ($info);
			$order['period']     = $order['order_date'];
			unset($order['s_id']);
			unset($order['p_id']);
			unset($order['time']);
			$this->http_post(self::HANLI_SEND_ORDER, $order);
		}

	}

	public function send2($ids) {
		if (!$ids) {
			Log::write("错误调用,无订单号!");
			return false;
		}
		/*
		 * {"order_id":"39","p_id":null,"total":"1","ctime":"1455585515","order_no":"2016021698525348","c_id":"000068","order_date":1450422000,"order_info":[{"id_num":"32028319900911614700008688","realname":"\u8463\u4e3d\u6167","sex":"0","tel":"13771125119","card_num":""}]}
*/
		$pre   = C("DB_PREFIX");
		$datas = M("customer")->alias('m')->join("left join {$pre}company c on c.name=m.company")
		                      ->field("m.card_num,m.company,m.dept,m.position,m.is_onsite,m.realname,m.sex,m.id_num,m.mobile,m.rank,m.level,m.married,c.code as company_code")
		                      ->where(array (
			                              'm.id'     => array ("IN", $ids),
			                              'm.status' => array ("EGT", 0)
		                              ))->select();

		$this->http_post(self::HANLI_SEND_ORDER2, $datas);
	}

	
	public function result() {
		echo((file_get_contents("php://input")));
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
		$strPOST = "inJson=" . $strPOST;
		Log::write("订单发送到汉立系统数据:" . $strPOST);
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCurl, CURLOPT_POST, true);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
//		curl_setopt($ch, CURLOPT_HTTPHEADER, array (
//			               'Content-Type: application/json',
//			               'Content-Length: ' . strlen($strPOST)
//		               )
//		);

		$sContent = curl_exec($oCurl);
		$aStatus  = curl_getinfo($oCurl);
		Log::write("订单发送到汉立系统结果:" . $sContent);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200) {
			return $sContent;
		} else {
			return false;
		}
	}
}