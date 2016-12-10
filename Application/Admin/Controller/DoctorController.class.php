<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Common\Model\DoctorModel;

/**
 * 后台回复管理控制器
 *
 */
class DoctorController extends AdminController {

	public function lists() {
		$db   = new DoctorModel();
		$data = $db->order("sort,id desc")->getAll();
		$this->assign('_list', $data['data']);
		$this->assign('_page', $data['page']);
		$this->meta_title = '名医列表';
		$this->display();
	}

	/* 新增回复 */
	public function add() {
		$db = new DoctorModel();
		if (IS_POST) { //提交表单
			$data = I("post.");
			if ($_FILES['pic']['size']) {
				$info        = $this->upload();
				$data['pic'] = './Uploads/' . $info['pic']['savepath'] . $info['pic']['savename'];
			}
			$data['ctime'] = time();
			if (false !== $db->add($data)) {
				$this->success('新增成功！', U('lists'));
			} else {
				$error = $db->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$this->meta_title = '新增名医信息';
			$this->display('add');
		}
	}

	/* 编辑回复 */
	public function edit($id = null) {
		$db = new DoctorModel();

		if (IS_POST) { //提交表单
			$data = I("post.");
			if ($_FILES['pic']['size']) {
				$info        = $this->upload();
				$data['pic'] = './Uploads/' . $info['pic']['savepath'] . $info['pic']['savename'];
			}
			if (false !== $db->save($data)) {
				$this->success('编辑成功！', U('lists'));
			} else {
				$error = $db->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$this->assign("data", $db->find($id));
			$this->meta_title = '编辑信息';
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
			$db = new DoctorModel();

			if ($db->where("id='$id'")->delete()) {
				$this->success('删除成功');
			} else {

				$this->error('删除失败');
			}
		}
	}

	private function upload() {
		$config = array (
			'maxSize'  => 3145728,
			'rootPath' => './Uploads/',
			'savePath' => '',
			'saveName' => array ('uniqid', ''),
			'exts'     => array ('jpg', 'gif', 'png', 'jpeg'),
			'autoSub'  => true,
			'subName'  => array ('date', 'Ymd'),
		);
		$upload = new \Think\Upload($config);// 实例化上传类

		// 上传文件
		$info = $upload->upload();
		if (!$info) {// 上传错误提示错误信息
			$this->error($upload->getError());
		} else {// 上传成功
			return $info;
		}
	}

}
