<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 Todd All rights reserved.
// +----------------------------------------------------------------------
// |  官方网址: vboshi.cn
// +----------------------------------------------------------------------
namespace Wechat\Controller;

use Think\Controller;
use Think\Page;

/* 预约管理控制器*/

class ReportController extends PublicController {
	
	public function _initialize() {
		parent::_initialize();
		
	}
	
	/* 预约管理首页*/
	public function index() {
		$this->assign("meta_title", "报告历史");
		$this->user = $this->get_login_user();
		$login_user = $this->user;
		if (session("user_auth")) {
			$this->user = M('customer')->where("openid='%s'", $this->openid)->find();
			session("user_auth", $this->user);
		} else {
			$this->user = M('customer')->where("openid='%s'", $this->openid)->find();
			if (!$this->user) {
				$this->redirect('Public/login');
			} else {
				session("user_auth", $this->user);
			}
			return $this->user;
		}
		
		
		$reports = M("report")->where(" id_num='%s'", $login_user['id_num'])->order('id desc')->limit(100)->select();
		foreach ($reports as &$_p) {
			$file_name   = explode("_", $_p['name']);
			$_p ["date"] = isset($file_name[3]) ? str_replace(".pdf", "", $file_name[3]) : "";
//			$_p ["id_num"]   = isset($file_name[2]) ? $file_name[2] : "";
//			$_p ["order_no"] = isset($file_name[1]) ? $file_name[1] : "";
		}
		
		$this->assign("list", $reports);
		$this->assign("openid", get_openid());
		$this->display();
	}
	
	
	public function down($id) {
		if (!$id) {
			$this->error("订单不存在!");
		} else {
			$user = M('customer')->where("openid='%s'", get_openid())->find();
			if (!$user) {
				$this->error("您未注册为会员", U("User/login"));
			}
			$login_user = $user;
			$report     = M("report")->where("id='%d' and id_num='%s'", $id, $login_user['id_num'])->find();
			if ($report) {
				$filename = $report['path'];
				if (file_exists($filename)) {
					header('Content-type: application/pdf');
					header("Content-Disposition: attachment; filename=" . basename($report['name']));
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
