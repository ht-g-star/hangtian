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
class DoctorController extends HomeController {

	public function _initialize() {
		parent::_initialize();
	}

	public function index() {
		$db      = new DoctorModel();
		$doctors = $db->getAll();
		$this->assign("_list", $doctors['data']);
		$this->assign("_page", $doctors['page']);

		$this->assign("meta_title", "名医堂");
		$this->display();
	}

	public function detail($id) {
		$db     = new DoctorModel();
		$doctor = $db->find($id);
		$this->assign("meta_title", $doctor['realname']);
		$this->assign("data", $doctor);
		$this->display();
	}


}
