<?php
/**
 * Created by PhpStorm.
 * User: litao
 * Date: 15/11/13
 * Time: 16:37
 */

namespace Admin\Model;


use Think\Model;

class ConsumeTypeModel extends Model {
	public static $reason;

	public static function getAll($uid) {
		$db = M("consume_type");
		if (is_administrator($uid)) {
			$data = $db->cache(true)->where('status=1')->getField("id,name");
			return $data;
		} else {
			$group_id = M("auth_group_access")->where("uid=%d", $uid)->order("uid desc")->getField("group_id");
			$type_ids = M("consume_type_auth")->where("group_id=%d", $group_id)->getField("type_ids");
			if ($type_ids) {
				$data         = $db->where(array (
					                           "status" => 1,
					                           "id"     => array ("in", $type_ids)
				                           ))->getField("id,name");
				self::$reason = $data;
			} else {
				self::$reason = false;
			}
		}

		return self::$reason;

	}

}