<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Wechat\Controller;

use Admin\Model\FcouponModel;

/**
 * 用户优惠券模型控制器
 * 文档模型列表和详情
 */
class UserCouponController extends FollowerController {


	public function index() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		/* 会员调用*/
		$uid = is_login();
		/* 优惠券调用*/

		$coupons      = new FcouponModel();
		$fcoupon      = $coupons->getAllCoupons(is_login());
		$fcoupon_used = $coupons->getAllUseredCoupons(is_login());

		$this->assign('couponlist', $fcoupon);
		$this->assign('couponusedlist', $fcoupon_used);
		$this->meta_title = '我的优惠券';
		$this->display();
	}

}
