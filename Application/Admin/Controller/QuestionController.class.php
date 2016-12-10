<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Common\Model\QuestionAnswerModel;
use Common\Model\QuestionCategoryModel;
use Common\Model\QuestionModel;

/**
 * 后台回复管理控制器
 *
 */
class QuestionController extends AdminController {

	public function lists() {
		$db   = new QuestionModel();
		$data = $db->getAll();
		$this->assign('_list', $data['data']);
		$this->assign('page', $data['page']);
		$this->meta_title = '咨询列表';
		$this->display();
	}

	/* 新增回复 */
	public function add() {
		$db = new QuestionModel();

		if (IS_POST) { //提交表单
			$data          = I("post.");
			$data['ctime'] = time();
			$data['vtime'] = 0;
			if (false !== $db->add($data)) {
				$this->success('新增成功！', U('lists'));
			} else {
				$error = $db->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$cat  = new QuestionCategoryModel();
			$data = $cat->getAll();
			if (empty($data['data'])) {
				$this->error("还没有咨询问题的分类,请先创建!", U("QuestionCategory/lists"));
			}
			$this->assign("cats", $data['data']);
			$this->meta_title = '新增咨询信息';
			$this->display('add');
		}
	}

	/* 编辑回复 */
	public function edit($id = null) {
		$cat = new QuestionCategoryModel();
		$db  = new QuestionModel();

		if (IS_POST) { //提交表单
			$data = I("post.");
			if (false !== $db->save($data)) {
				$this->success('编辑成功！', U('lists'));
			} else {
				$error = $db->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$this->assign("data", $db->find($id));
			$data = $cat->getAll();
			if (empty($data['data'])) {
				$this->error("还没有咨询问题的分类,请先创建!", U("QuestionCategory/lists"));
			}
			$this->assign("cats", $data['data']);
			$this->meta_title = '编辑咨询';
			$this->display("add");
		}
	}


	/**
	 * 删除一个回复
	 *
	 */
	public function del() {
		if (IS_GET) {
			$id = I('get.id');
			$db = new QuestionModel();

			if ($db->where("id='$id'")->delete()) {
				$this->success('删除成功');
			} else {

				$this->error('删除失败');
			}
		}
	}


	public function answer($id) {
		$this->meta_title = '回答列表';
		$model            = new QuestionAnswerModel();
		if (IS_POST) {
			$res = $model->add(array (
				                   "doctor"  => I('post.doctor'),
				                   "content" => I("post.content"),
				                   "q_id"    => $id,
				                   "ctime"   => time()
			                   ));
			if ($res) {
				M("question")->where("id=%d", $id)->setField("vtime", time());
				$this->success("回答成功");
			} else {
				$this->error("数据库错误");
			}
		} else {
			$data     = $model->where("q_id=%d", $id)->select();
			$question = M("question")->find($id);
			$this->assign("question", $question);
			$this->assign("ans", $data);
			$this->display();
		}
	}

}
