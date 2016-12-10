<?php
namespace Common\Api;

class GatApi {

	// 非正式
	const API_HOST_GAT    = 'https://api.guanaitong.com';
	const APP_ID_GAT      = '20091123';
	const APP_SERECT_GAT  = '6cdf512baf3bc14f2d2505412ca1af21';

	const PREFIX_CORP_ID  = 'HTWX';

	/**
	 * 积分发放
	 * @param string  $order_code       交易号
	 * @param number  $money            金额
	 * @param string  $reason           发放原因
	 * @param string  $email
	 * @param string  $mobile
	 * @param string  $corp_id          工号
	 * @param string  $approver_corp_id 审核人工号
	 * @return Array
	 */
	public static function  jifen_assign($order_code, $money, $reason, $corp_id = '', $mobile = '', $email = '', $approver_corp_id = ''){
		$actionPath = '/v3/enterprise/jifen/assign/single';
		$actionUrl = self::API_HOST_GAT.$actionPath;
		$params = array (
			'appid'            => self::APP_ID_GAT,
			'external_code'    => $order_code,
			'amount'           => $money,
			'reason'           => $reason,
			'corp_id'          => $corp_id ? self::PREFIX_CORP_ID.$corp_id : '', //gat平台工号
			'email'            => $email,
			'mobile'           => $mobile,
			'approver_corp_id' => $approver_corp_id,
			'timestamp'        => date('YmdHis'),
			);
		$sign = self::gen_signature($params);
		$params = array_merge($params, array('sign' => $sign, 'timestamp' => date('YmdHis')));
		$result = self::request($actionUrl, http_build_query($params));
		return json_decode($result, true);
	}

	/**
	 * 查询员工积分
	 * @param string $corp_id  员工工号
	 * @return Array
	 */
	public static function jifen_get_employee($corp_id){
		$actionPath = '/v3/enterprise/employee/jifen/get';
		$actionUrl = self::API_HOST_GAT.$actionPath;
		$params = array(
			'appid'     => self::APP_ID_GAT,
			'corp_id'   => self::PREFIX_CORP_ID.$corp_id,
			'timestamp' => date('YmdHis'),
			);
		$sign = self::gen_signature($params);
		$params = array_merge($params, array('sign' => $sign, 'timestamp' => date('YmdHis', time())));
		$result = self::request($actionUrl, http_build_query($params));
		return json_decode($result, true);
	}

	/**
	 * 添加员工
	 * @param string $corp_id          员工工号
	 * @param string $name             员工姓名
	 * @param string $mobile           手机
	 * @param string $gender           性别
	 * @param string $birth_date       出生日期
	 * @param string $entry_date       入职日期
	 * @param string $email            邮箱
	 * @param int    $invite_by_email  是否发送邀请邮件, 1:发送，2:不发送。默认不发送 
	 * @param string $phone            固定电话号码
	 * @param int    $card_type        证件类型
	 * @param string $card_no          身份证号或者护照号码
	 * @return Array
	 */
	public static function employee_add($corp_id, $name, $mobile = '', $gender = '', $birth_date = '', $entry_date = '', $email = '', 
		$invite_by_email = '', $phone = '', $card_type = '', $card_no = '') {
		$actionPath = '/v3/enterprise/employee/add';
		$actionUrl = self::API_HOST_GAT.$actionPath;
		$params = array (
			'appid'            => self::APP_ID_GAT,
			'corp_id'          => self::PREFIX_CORP_ID.$corp_id,
			'name'             => $name,
			'gender'           => $gender,
			'birth_date'       => $birth_date,
			'entry_date'       => $entry_date,
			'email'            => $email,
			'invite_by_email'  => $invite_by_email,
			'mobile'           => $mobile,
			'phone'            => $phone,
			'card_type'        => $card_type,
			'card_no'          => $card_no,
			'timestamp'        => date('YmdHis'),
			);
		$sign = self::gen_signature($params);
		$params = array_merge($params, array('sign' => $sign, 'timestamp' => date('YmdHis', time())));
		$result = self::request($actionUrl, http_build_query($params));
		return json_decode($result, true);
	}

	/**
	 * 信任登陆，step1:获取授权码
	 * @param string $corp_id      员工工号
	 * @param string $return_url   重定向
	 * @return Array
	 */
	public static function get_auth_code($corp_id, $return_url=null) {
		$actionPath = '/v3/enterprise/employee/get_auth_code';
		$actionUrl = self::API_HOST_GAT.$actionPath;
		$params = array (
			'appid'      => self::APP_ID_GAT,
			'corp_id'    => self::PREFIX_CORP_ID.$corp_id,
			'return_url' => $return_url,
			'timestamp'  => date('YmdHis'),
			);
		$sign = self::gen_signature($params);
		$params = array_merge($params, array('sign' => $sign, 'timestamp' => date('YmdHis', time())));
		$result = self::request($actionUrl, http_build_query($params));
		return json_decode($result, true);		
	}

	/**
	 * 信任登陆，step2:登陆
	 * @param string $auth_code  临时授权码,1min
	 */
	public static function login($auth_code) {
		$actionPath = '/v3/enterprise/employee/login';
		$actionUrl = self::API_HOST_GAT.$actionPath;
		header("Location:$actionUrl?".http_build_query(array('auth_code' => $auth_code)));
	} 

	/* 生成签名 */
	private static function gen_signature($params){
		$params = array_filter($params); //过滤
		ksort($params); //键名排序
		$tempStr = self::APP_SERECT_GAT;
		foreach ($params as $k => $v) {
			$tempStr .= $k;
			$tempStr .= $v;
		}
		return strtoupper(sha1($tempStr));
	}

	private static function request($url, $data, $method = 'POST', $timeout = 60) {
		$urlarr = parse_url($url);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
		if (strtolower($urlarr['scheme']) == 'https')
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);	
		}
		if ($urlarr['port'])
			curl_setopt($ch, CURLOPT_PORT, $urlarr['port']);
		if (strtoupper($method) == 'POST')
		{
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		else //GET method
		{
			if ($data)
			{
				if (false===strpos($url, '?'))
					$url .= '?'.$data;
				else
					$url .= '&'.$data;
			}
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}
