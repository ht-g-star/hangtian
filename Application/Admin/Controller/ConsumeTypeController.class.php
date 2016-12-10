<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 后台配置控制器
 *
 */
class ConsumeTypeController extends AdminController {

	/**
	 * 配置管理
	 *
	 */
	public function lists() {
		/* 查询条件初始化 */
		$map = array ('status' => 1);

		$lists = M("consume_type")->where($map)->select();
		// 记录当前列表页的cookie
		Cookie('__forward__', $_SERVER['REQUEST_URI']);

		$this->assign('_list', $lists);
		$this->meta_title = '消费类型管理';
		$this->display();
	}

	/**
	 * 新增配置
	 *
	 */
	public function add() {
		if (IS_POST) {
			$model = D('consume_type');
			$data  = $model->create();
			if ($data) {
				if ($model->add()) {
					$this->success('新增成功', U('lists'));
					addUserLog("新增了消费类型", is_login(), false);
				} else {
					$this->error('新增失败');
				}
			} else {
				$this->error($model->getError());
			}
		} else {
			$this->meta_title = '新增类型';
			$this->assign('info', null);
			$this->display('edit');
		}
	}

	public function edit($id = 0) {
		if (IS_POST) {
			$Config = D('consume_type');
			$data   = $Config->create();
			if ($data) {
				if ($Config->save()) {
					//记录行为
					$this->success('更新成功', Cookie('__forward__'));
					addUserLog("修改了消费类型", is_login(), false);
				} else {
					$this->error('更新失败');
				}
			} else {
				$this->error($Config->getError());
			}
		} else {
			$info = array ();
			/* 获取数据 */
			$info = M('consume_type')->field(true)->find($id);

			$this->assign('data', $info);
			$this->meta_title = '编辑配置';
			$this->display();
		}
	}


	/**
	 * 删除配置
	 *
	 */
	public function del() {
		$id = array_unique((array)I('id', 0));

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$map = array ('id' => array ('in', $id));
		if (M('consume_type')->where($map)->setField('status', 0)) {
			addUserLog("删除了消费类型", is_login(), false);
			$this->success('删除成功');
		} else {
			$this->error('删除失败！');
		}
	}

	public function setAuth($group_id) {
		$this->meta_title = '消费类别授权';

		if (IS_POST) {
			$type_ids = I("post.type_ids");
			if ($type_ids) {
				$type_ids = implode(",", $type_ids);
			} else {
				$this->error("请选择至少一个");
			}
			$exist = M("consume_type_auth")->where("group_id=%d", $group_id)->find();
			if ($exist) {
				$res = M("consume_type_auth")->where("group_id=%d", $group_id)->setField("type_ids", $type_ids);
			} else {
				$res = M("consume_type_auth")->add(array (
					                                   "group_id" => $group_id,
					                                   "type_ids" => $type_ids
				                                   )
				);
			}
			if ($res) {
				$this->success("授权成功!");
			} else {
				$this->error("授权失败!");
			}
		} else {
			$map   = array ('status' => 1);
			$lists = M("consume_type")->where($map)->select();

			$selected = M("consume_type_auth")->where("group_id=%d", $group_id)->find();
			$this->assign("selected", explode(",", $selected['type_ids']));


			$this->assign("lists", $lists);
			$this->display();
		}

	}

}