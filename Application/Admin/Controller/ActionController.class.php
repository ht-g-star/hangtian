<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 行为控制器
 *
 */
class ActionController extends AdminController {

	/**
	 * 行为日志列表
	 *
	 */
	public function actionLog() {
		//获取列表数据
		$map['source'] = 0;
		$uid           = I("uid", 0, 'trim');
		$starttime     = I("starttime", 0, 'trim');
		$endtime       = I("endtime", 0, 'trim');

		if ($uid) {
			$map['uid'] = $uid;
		}
		if ($starttime) {
			$map['create_time'][] = array ("egt", strtotime($starttime));
		}
		if ($endtime) {
			$map['create_time'][] = array ("elt", strtotime($endtime));
		}
		$list = $this->lists('user_log', $map);
		foreach ($list as $key => $value) {
			$model_id                 = get_document_field($value['model'], "name", "id");
			$list[ $key ]['model_id'] = $model_id ? $model_id : 0;
		}
		$this->assign('_list', $list);
		$this->meta_title = '行为日志';
		$this->assign("users", M("member")->where("status >=1")->select());

		$this->display();
	}
//    public function actionLog(){
//        //获取列表数据
//        $map['status']    =   array('gt', -1);
//        $list   =   $this->lists('ActionLog', $map);
//        int_to_string($list);
//        foreach ($list as $key=>$value){
//            $model_id                  =   get_document_field($value['model'],"name","id");
//            $list[$key]['model_id']    =   $model_id ? $model_id : 0;
//        }
//        $this->assign('_list', $list);
//        $this->meta_title = '行为日志';
//        $this->display();
//    }

	/**
	 * 查看行为日志
	 *
	 */
	public function edit($id = 0) {
		empty($id) && $this->error('参数错误！');

		$info = M('ActionLog')->field(true)->find($id);

		$this->assign('info', $info);
		$this->meta_title = '查看行为日志';
		$this->display();
	}

	/**
	 * 删除日志
	 * @param mixed $ids
	 *
	 */
	public function remove($ids = 0) {
		empty($ids) && $this->error('参数错误！');
		if (is_array($ids)) {
			$map['id'] = array ('in', $ids);
		} elseif (is_numeric($ids)) {
			$map['id'] = $ids;
		}
		$res = M('ActionLog')->where($map)->delete();
		if ($res !== false) {
			$this->success('删除成功！');
		} else {
			$this->error('删除失败！');
		}
	}

	/**
	 * 清空日志
	 */
	public function clear() {
		$res = M('ActionLog')->where('1=1')->delete();
		if ($res !== false) {
			$this->success('日志清空成功！');
		} else {
			$this->error('日志清空失败！');
		}
	}

}
