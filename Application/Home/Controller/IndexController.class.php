<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
namespace Home\Controller;

use Common\Model\QuestionCategoryModel;
use Common\Model\QuestionModel;
use OT\DataDictionary;
use User\Api\UserApi;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 * $url= $_SERVER[HTTP_HOST]; //获取当前域名
 */
class IndexController extends HomeController {


	/**系统首页**/
	public function index() {
		// cookie自动登录
		if (!is_login() && cookie('username') && cookie('password')) {
			$username = cookie('username');
			$password = cookie('password');
			$username = safe_replace($username);//过滤
			$user     = new UserApi;
			$uid      = $user->login($username, $password);
			if (0 < $uid) { //UC登录成功
				/* 登录用户 */
				$Member = D("Member");
				if ($Member->login($uid)) {
					//登录用户，记录日志
					addUserLog('cookie登陆成功', $uid,true);
				}
			}
		}
		if (1 == C('IP_TONGJI')) {
			$title = "index";
			/**首页统计代码实现 status=1**/
			$record = IpLookup("", 1, $title);
		}
		/** 幻灯片* */
//		$slide = D('slide')->get_slide();
//		$this->assign('slide', $slide);
		/** 顶级栏目* */
		$tree = D('Category')->maketree();
		$this->assign('tree', $tree);
		/** 公告分类**/
//		$notice = M('document')->order('id desc')->where("category_id='56'")->limit(8)->select();
//		$this->assign('notice', $notice);
		/** 活动分类**/
//		$activity = M('document')->order('id desc')->where("category_id='70'")->limit(8)->select();
//		$this->assign('activity', $activity);
		$suites1 = M("suite")->field("id,title,price_sex_0,price_sex_1")->where("status=1")->limit(3)->order("sort")->select();
		$suites2 = M("suite")->field("id,title,price_sex_0,price_sex_1")->where("status=1")->limit(6)->order("sort desc")->select();
		$this->assign("suite1", $suites1);
		$this->assign("suite2", $suites2);

		$model     = new QuestionModel();
		$category  = new QuestionCategoryModel();
		$categorys = $category->getAll();
		$this->assign("category", $categorys['data']);
		$quest = $model->getAll(100);
		$this->assign("data", $quest);

		$lessons = M("Lesson")->field("id,title,content,ctime,pic")->order("ctime desc")->limit(6)->select();
		$this->assign("lessons", $lessons);

		$doctors = M("Doctor")->field("id,title,ctime,pic,realname")->order("sort")->limit(8)->select();
		$this->assign("doctors", $doctors);

		$this->meta_title = '首页';
		$this->display();
	}

	public function getGoodlist($cateid = null) {
		$cateid = I('cateid', 0, 'intval'); // 用intval过滤$_POST['id'];
		$data   = D('Category')->getDatalist($cateid);
		$this->ajaxReturn($data);
	}
 
	public function appointment(){
		$api = A('Api/User');
		$api->login51URL(I('target'));
	}
}