<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;

use Common\Model\DoctorModel;
use Think\Controller;

/**
 */
class EnvController extends HomeController {

	public function _initialize() {
		parent::_initialize();
	}

	public function index() {

		$this->assign("meta_title", "配套服务");
		$this->display();
	}

}
