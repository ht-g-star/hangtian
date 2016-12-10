<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 1010422715@qq.com All rights reserved.
// +----------------------------------------------------------------------
// | author 烟消云散 <1010422715@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Model;

use Think\Model;

/**
 * 优惠券模型
 *
 */
class FcouponModel extends Model {

	protected $_validate
		= array (
			array ('title', 'require', '名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
			array ('price', 'require', '优惠金额不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
		);


	/**
	 * 获取优惠券详细信息
	 * @param  milit $id 优惠券ID或标识
	 * @param  boolean $field 查询字段
	 * @return array     优惠券信息
	 *
	 */
	public function info($id, $field = true) {
		/* 获取优惠券信息 */
		$map = array ();
		if (is_numeric($id)) { //通过ID查询
			$map['id'] = $id;
		} else { //通过标识查询
			$map['name'] = $id;
		}
		return $this->field($field)->where($map)->find();
	}

	/**
	 * 获取优惠券树，指定优惠券则返回指定优惠券极其子优惠券，不指定则返回所有优惠券树
	 * @param  integer $id 优惠券ID
	 * @param  boolean $field 查询字段
	 * @return array          优惠券树
	 *
	 */


	/**
	 * 更新优惠券信息
	 * @return boolean 更新状态
	 *
	 */
	public function update() {
		$data                     = $this->create();
		$this->data['display']    = 1;
		$this->data['start_time'] = strtotime(I('post.start_time', 'trim'));
		$this->data['end_time']   = strtotime(I('post.end_time', 'trim'));
		if (!$data) { //数据对象创建错误
			return false;
		}

		/* 添加或更新数据 */
		if (empty($data['id'])) {
			$this->data['create_time'] = NOW_TIME;
			$this->data['update_time'] = NOW_TIME;
			$res                       = $this->add();
		} else {
			$this->data['update_time'] = NOW_TIME;
			$res                       = $this->save();
		}
		//更新优惠券缓存
		S('sys_fcoupon_list', null);
		//记录行为
		addUserLog('更新优惠券' . $data['id'], UID);
		return $res;
	}

	/**
	 * 获取可以使用的优惠券
	 */
	public function getCoupons($uid, $total = 0) {
		$user  = M("customer")->find($uid);
		$rank  = $user['rank'];
		$ids   = M("user_coupon")->where("uid=%d and status=2", $uid)->getField("couponid", true);//用过了
		$now   = NOW_TIME;
		$where = "(`user_rank` = {$rank}  or `user_rank`=-1 )  AND( (`start_time` <= {$now} AND `end_time` >= {$now}) or start_time=0 or end_time=0) and status=1 and lowpayment<={$total} ";
		$ids   = implode(",", $ids);
		if ($ids) {
			$where .= "and id not in ({$ids})";
		}
		$coupons = $this->where($where)->limit(10)->getField("id,title,price,lowpayment,description,icon,start_time,end_time,user_rank,type");


		return $coupons;
	}

	/**
	 * 获取全部的优惠券
	 */
	public function getAllCoupons($uid) {
		$user  = M("customer")->find($uid);
		$rank  = $user['rank'];
		$ids   = M("user_coupon")->where("uid=%d and status=2", $uid)->getField("couponid", true);//用过了
		$now   = NOW_TIME;
		$where = "(`user_rank` = {$rank}  or `user_rank`=-1 )  AND( (`start_time` <= {$now} AND `end_time` >= {$now}) or start_time=0 or end_time=0) and status=1  ";
		$ids   = implode(",", $ids);
		if ($ids) {
			$where .= "and id not in ({$ids})";
		}
		$coupons = $this->where($where)->limit(20)->getField("id,title,price,lowpayment,description,icon,start_time,end_time,user_rank,type");


		return $coupons;
	}

	/**
	 * 获取全部的用过的优惠券
	 */
	public function getAllUseredCoupons($uid) {
		$user  = M("customer")->find($uid);
		$rank  = $user['rank'];
		$ids   = M("user_coupon")->where("uid=%d and status=2", $uid)->getField("couponid", true);//用过了
		$now   = NOW_TIME;
		$where = "(`user_rank` = {$rank}  or `user_rank`=-1 )  AND( (`start_time` <= {$now} AND `end_time` >= {$now}) or start_time=0 or end_time=0) and status=1  ";
		$ids   = implode(",", $ids);
		if ($ids) {
			$where .= "and id  in ( {$ids})";
		}else{
			return false;
		}
		$coupons = $this->where($where)->limit(20)->getField("id,title,price,lowpayment,description,icon,start_time,end_time,user_rank,type");

		return $coupons;
	}

}
