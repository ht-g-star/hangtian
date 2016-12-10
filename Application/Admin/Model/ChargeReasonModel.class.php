<?php
/**
 * Created by PhpStorm.
 * User: litao
 * Date: 15/11/13
 * Time: 16:37
 */

namespace Admin\Model;


class ChargeReasonModel {
	//0 为不发短信
	private static $reason
		= array (
			1 => "前台充值",
			2 => "自主充值"
		);
	
	public static function getReason($code) {
		if (isset(self::$reason[ $code ])) {
			return self::$reason[ $code ];
		} else {
			return $code;
		}

	}
	
	public static function getAllReason() {
		return self::$reason;
	}
}