<?php
namespace Wechat\Controller;

use Org\Wechat\TPWechat;
use Org\Wechat\Wechat;
use Think\Controller;
use Think\Log;

class MallController extends FollowerController {

	public function _initialize() {
		$this->assign("meta_title", "健康商城");
		parent::_initialize();
	}

	public function index() {
		$suites = M("suite")->field("id,title,pic,price_sex_0,price_sex_1")
		                    ->where("status>%d", \Admin\Controller\SuiteController::STATUS_DELETED)
		                    ->order("sort")->limit(3)->select();
		$this->assign("suites", $suites);
		$tree = D('Home/Category')->maketree();
		$this->assign('tree', $tree);
		$this->display();
	}

	public function list1(){
		$this->display();
	}

}



