<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Common\Model\QuestionCategoryModel;
use Common\Model\QuestionModel;

/**
 *
 */
class QuestionCategoryController extends AdminController {

	public function lists() {
		$db   = new QuestionCategoryModel();
		$data = $db->getAll();
		$this->assign('_list', $data['data']);
		$this->assign('page', $data['page']);
		$this->meta_title = '咨询分类列表';
		$this->display();
	}

	public function add() {

		if (IS_POST) { //提交表单
			$db = new QuestionCategoryModel();
			if (false !== $db->add(I("post."))) {
				$this->success('新增成功！', U('lists'));
			} else {
				$error = $db->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$this->meta_title = '新增咨询分类';
			$this->display();
		}
	}

	/* 编辑回复 */
	public function edit($id = null) {
		$db = new QuestionCategoryModel();

		if (IS_POST) { //提交表单
			$data = I("post.");
			if (false !== $db->save($data)) {
				$this->success('编辑成功！', U('lists'));
			} else {
				$error = $db->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$this->assign('data', $db->find($id));
			$this->meta_title = '编辑咨询分类';
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
			$db = new QuestionCategoryModel();
			if ($db->where("id='$id'")->delete()) {
				$this->success('删除成功');
			} else {

				$this->error('删除失败');
			}
		}
	}


}
