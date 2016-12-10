<?php
/**
 * Created by PhpStorm.
 * User: litao
 * Date: 15/11/13
 * Time: 16:37
 */

namespace Admin\Model;


class ConsumeReasonModel {
	private static $reason
		= array (
			1 => "前台消费",
			2 => "普通消费"
		);
	
	public static function getReason($code) {
		return self::$reason[ $code ];
	}
	
	public static function getAllReason() {
		return self::$reason;
	}
}