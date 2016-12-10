<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Common\Model\GoodsCommentsModel;
use Common\Model\GoodsCommentsModelModel;


/**
 * 后台回复管理控制器
 *
 */
class CommentsController extends AdminController {

	public function lists() {
		$db   = new GoodsCommentsModel();
		$data = $db->getAll();
		$this->assign('_list', $data['data']);
		$this->assign('page', $data['page']);
		$this->meta_title = '商品留言列表';
		$this->display();
	}

	/* 新增回复
	public function add() {
		$db = new GoodsCommentsModel();

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
			$this->assign("cats", $data['data']);
			$this->meta_title = '新增咨询信息';
			$this->display('add');
		}
	}*/

	/* 编辑回复 */
	public function edit($id = null) {
		$db = new GoodsCommentsModel();

		if (IS_POST) { //提交表单
			$data          = I("post.");
			$data['vtime'] = NOW_TIME;
			if (false !== $db->save($data)) {
				$this->success('编辑成功！', U('lists'));
			} else {
				$error = $db->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$this->assign("data", $db->find($id));
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
			$db = new GoodsCommentsModel();

			if ($db->where("id='$id'")->delete()) {
				$this->success('删除成功');
			} else {
				$this->error('删除失败');
			}
		}
	}

}
