<?php
namespace Vendor\Sms;

use Think\Log;

class Sms {
	private static $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";//短信post提交地址;
	//var $username='用户名';//互亿短信平台上注册的用户名，每个账号可以获得10条测试短信;
	//var $password='密码';//密码;


	public  $err = "";
	private $queue;
	private $map;

	function __construct() {
		$this->queue = curl_multi_init();
		$this->map   = array ();
		$this->Sms();
	}

	function Sms() {
	}

	//curl post 方式提交请求获取响应信息。
	function Post($curlPost, $url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
		$return_str = curl_exec($curl);
		curl_close($curl);
		return $return_str;
	}

	//将xml格式的数据转化成为数组
	function xml_to_array($xml) {
		$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
		if (preg_match_all($reg, $xml, $matches)) {
			$count = count($matches[0]);
			for ($i = 0; $i < $count; $i++) {
				$subxml = $matches[2][ $i ];
				$key    = $matches[1][ $i ];
				if (preg_match($reg, $subxml)) {
					$arr[ $key ] = $this->xml_to_array($subxml);
				} else {
					$arr[ $key ] = $subxml;
				}
			}
		}
		return $arr;
	}


	function send($mobile, $content) {

		$switch = C('SMS_SWITCH');
		if (!$switch) {
			$this->err = "短信功能被关闭";
			return false;
		}
		if (!($_SERVER['HTTP_HOST'] == 'ht738.vboshi.cn' || $_SERVER['HTTP_HOST'] == 'www.wx738.com' || $_SERVER['HTTP_HOST'] == '192.168.0.171' || $_SERVER['HTTP_HOST'] == 'ht738.local')) {
			$this->err = "网址错误!";
			return false;
		}
		if (empty($mobile) || strlen($mobile) != 11) {
			$this->err = "手机号码不存在或者格式错误";
			return false;
		}


		$_count = M("sms")->where("mobile='%s' and create_time > %d", $mobile, (time() - 24 * 3600))->count('id');
		if ($_count > 10) {
			Log::write("号码 {$mobile}发送次数超过{$_count}次");
			$this->err = "您的号码24小时内发送太多次,请明天再试!";
			return false;
		}


		$post_data = "account=" . C('SMSACCOUNT') . "&password=" . C('SMSPASSWORD') . "&mobile=" . $mobile . "&content=" . rawurlencode($content);//密码可以使用明文密码或使用32位MD5加密
		$gets      = $this->xml_to_array($this->Post($post_data, self::$target));
		if ($gets['SubmitResult']['code'] == 2) {//返回码等于2的时候
			$result = "发送成功！";
			//短信记录
			$sms  = M("sms");
			$data = array (
				"content"     => $content,
				"mobile"      => $mobile,
				"create_time" => time(),
				"group"       => 2,
				'extcode'     => 2
			);
			$sql  = $sms->fetchSql(true)->add($data);
		} else {
			$sms       = M("sms");
			$data      = array (
				"content"     => $content,
				"mobile"      => $mobile,
				"create_time" => time(),
				"group"       => 2,
				"status"      => -1,
				'extcode'     => isset($gets['SubmitResult']['code']) ? $gets['SubmitResult']['code'] : -1
			);
			$sql       = $sms->fetchSql(true)->add($data);
			$result    = '发送失败！,错误码' . $gets['SubmitResult']['code'];
			$this->err = $result;
		}
		M()->execute($sql);
		return $result;
	}


	private $sql;

	public function add_send_multi($mobile, $content) {

		$switch = C('SMS_SWITCH');
		if (!$switch) {
			$this->err = "短信功能被关闭";
			return false;
		}
		if (!($_SERVER['HTTP_HOST'] == 'ht738.vboshi.cn' || $_SERVER['HTTP_HOST'] == 'www.wx738.com' || $_SERVER['HTTP_HOST'] == '192.168.0.171' || $_SERVER['HTTP_HOST'] == 'ht738.local')) {
			$this->err = "网址错误!";
//			return false;
		}
		if (empty($mobile) || strlen($mobile) != 11) {
			$this->err = "手机号码不存在或者格式错误";
			return false;
		}

		$_count = M("sms")->where("mobile=' % s' and create_time > %d", $mobile, (time() - 24 * 3600))->count('id');
		if ($_count > 10) {
			Log::write("号码 {$mobile}发送次数超过{$_count}次");
			$this->err = "您的号码24小时内发送太多次,请明天再试!";
			return false;
		}

		$post_data = "account=" . C('SMSACCOUNT') . "&password=" . C('SMSPASSWORD') . "&mobile=" . $mobile . "&content=" . rawurlencode($content);//密码可以使用明文密码或使用32位MD5加密


		$sms  = M("sms");
		$data = array (
			"content"     => $content,
			"mobile"      => $mobile,
			"create_time" => time(),
			"group"       => 2,
			'extcode'     => 2,
			'status'      => 0
		);
		$this->sql .= $sms->fetchSql(true)->add($data) . ";";
		$this->add_post($post_data, self::$target);
	}

	public function send_multi() {

//		if ($gets['SubmitResult']['code'] == 2) {//返回码等于2的时候
//			$result = "发送成功！";
//			//短信记录
//
//		} else {
//			$sms       = M("sms");
//			$data      = array (
//				"content"     => $content,
//				"mobile"      => $mobile,
//				"create_time" => time(),
//				"group"       => 2,
//				"status"      => -1,
//				'extcode'     => isset($gets['SubmitResult']['code']) ? $gets['SubmitResult']['code'] : -1
//			);
//			$sql       = $sms->fetchSql(true)->add($data);
//			$result    = '发送失败！,错误码' . $gets['SubmitResult']['code'];
//			$this->err = $result;
//		}

//		dump($this->sql);
		M()->execute($this->sql);

//		return $result;

		$responses = array ();
		do {
			while (($code = curl_multi_exec($this->queue, $active)) == CURLM_CALL_MULTI_PERFORM) ;
			if ($code != CURLM_OK) {
				break;
			}
			while ($done = curl_multi_info_read($this->queue)) {
//				$error   = curl_error($done['handle']);
//				$results = curl_multi_getcontent($done['handle']);
//				if ($this->xml_to_array($results)) {
//					$results = $this->xml_to_array($results);
//				}
//				$responses[ $this->map[ (string)$done['handle'] ] ] = compact('error', 'results');
				curl_multi_remove_handle($this->queue, $done['handle']);
				curl_close($done['handle']);
			}
			if ($active > 0) {
				curl_multi_select($this->queue, 0.5);
			}
		} while ($active);
		curl_multi_close($this->queue);
		return $responses;
	}


	//curl post 方式提交请求获取响应信息。
	private function add_post($curlPost, $url) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOSIGNAL, true);
		curl_multi_add_handle($this->queue, $ch);


//		$curl = curl_init();
//		curl_setopt($curl, CURLOPT_URL, $url);
//		curl_setopt($curl, CURLOPT_HEADER, false);
//		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//		curl_setopt($curl, CURLOPT_NOBODY, true);
//		curl_setopt($curl, CURLOPT_POST, true);
//		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
//		$return_str = curl_exec($curl);
//		curl_close($curl);
//		return $return_str;
	}

}

?>