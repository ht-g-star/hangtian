<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Wechat\Controller;

use Common\Model\LessonModel;
use Think\Controller;

/**
 */
class LessonController extends FollowerController {

	public function _initialize() {
		parent::_initialize();
	}

	public function index() {
		$this->assign("meta_title", "健康讲坛");
		$db   = new LessonModel();
		$data = $db->order("sort,id desc")->getAll();
		$this->assign('_list', $data['data']);
		$this->assign('_page', $data['page']);
		$this->display();
	}

	public function detail($id) {
		$db   = new LessonModel();
		$data = $db->find($id);
		$this->assign("meta_title", $data['title']);
		$this->assign("data",$data);
		$this->display();
	}

}
