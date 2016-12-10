<?php
/**
 * Created by PhpStorm.
 * User: litao
 * Date: 15/11/13
 * Time: 16:37
 */

namespace Admin\Model;


class BalanceReasonModel {
	private static $reason
		= array (
			0 => "系统调整",
			1 => "批量扣款",
			2 => "批量充值",
		);
	
	public static function getReason($code) {
		return self::$reason[ $code ];
	}
	
	public static function getAllReason() {
		return self::$reason;
	}
}