<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;

use Common\Model\QuestionAnswerModel;
use Common\Model\QuestionCategoryModel;
use Common\Model\QuestionModel;
use Think\Controller;

/**
 */
class QuestionController extends HomeController {

	public function _initialize() {
		parent::_initialize();
	}

	public function index() {
		$model = new QuestionModel();
		if (IS_POST) {
			$v_code = I("post.v_code");
			$verify = new \Think\Verify();
			if (!$verify->check($v_code, 2)) {
//				Log::write("验证码错误." . get_client_ip());
				$this->error("验证码错误");
				exit("");
				return;
			}

			$login_user     = session("user_auth");
			$data           = I("post.");
			$data['ctime']  = time();
			$data["uid"]    = $login_user['id'];
			$data["c_name"] = $login_user['realname'];
			$data['vtime']  = 0;

			$id = $model->add($data);
			if ($id) {
				$this->success("发布成功", U("Question/detail", array ('id' => $id)));
			} else {
				$this->error("出现错误,请重试!");
			}
		} else {
			$category  = new QuestionCategoryModel();
			$categorys = $category->getAll();
			$this->assign("category", $categorys['data']);
			$quest = $model->getAll();
			$this->assign("data", $quest['data']);
			$this->assign("page", $quest['page']);
			$this->assign("meta_title", "健康咨询");
			$this->display();
		}

	}

	public function detail($id) {
		$model    = new QuestionModel();
		$question = $model->find($id);
		$this->assign("question", $question);
		$this->assign("meta_title", $question['title']);

		$ans_model = new QuestionAnswerModel();
		$ans       = $ans_model->getByQId($id);
		$this->assign("answers", $ans);
		$this->display();
	}

	public function lists() {
		$category  = new QuestionCategoryModel();
		$categorys = $category->getAll();
		$this->assign("category", $categorys['data']);

		$model = new QuestionModel();
		$cid   = I("get.cid");
		if ($cid) {
			$quest = $model->getByCId($cid);
		} else {
			$quest = $model->getAll();

		}
		$this->assign("data", $quest['data']);
		$this->assign("page", $quest['page']);
		$this->assign("meta_title", "健康咨询");
		$this->display();

	}

}
