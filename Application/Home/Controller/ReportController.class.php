<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 Todd All rights reserved.
// +----------------------------------------------------------------------
// |  官方网址: vboshi.cn
// +----------------------------------------------------------------------
namespace Home\Controller;

use Think\Controller;
use Think\Page;

/* 预约管理控制器*/

class ReportController extends HomeController {

	public function _initialize() {
		parent::_initialize();

	}

	/* 预约管理首页*/
	public function index() {
		$this->assign("meta_title", "报告历史");
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$login_user = session("user_auth");


		$reports = M("report")->where(" id_num='%s'", $login_user['id_num'])->order('id desc')->select();
		foreach ($reports as &$_p) {
			$file_name   = explode("_", $_p['name']);
			$_p ["date"] = isset($file_name[3]) ? str_replace(".pdf", "", $file_name[3]) : "";
//			$_p ["id_num"]   = isset($file_name[2]) ? $file_name[2] : "";
//			$_p ["order_no"] = isset($file_name[1]) ? $file_name[1] : "";
		}
		
		$this->assign("list", $reports);
		$this->display();
	}
	


	public function down($id) {
		if (!$id) {
			$this->error("订单不存在!");
		} else {
			if (!is_login()) {
				$this->error("您还没有登陆", U("User/login"));
			}
			$login_user = session("user_auth");

			$report = M("report")->where("id='%d' and id_num='%s'", $id, $login_user['id_num'])->find();
			if ($report) {
				$filename = $report['path'];
				if (file_exists($filename)) {
					header('Content-type: application/pdf');
//				header("Content-Disposition: attachment; filename=" . basename($filename));
					readfile($filename);
					exit;
				} else {
					$this->error("此报告暂时不存在,您可以去报告历史查询.");
				}
			} else {
				$this->error("此报告不存在");
			}
		}
	}
}
