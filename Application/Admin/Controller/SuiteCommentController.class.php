<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Admin\Model\BalanceReasonModel;
use Admin\Model\ChargeReasonModel;
use Admin\Model\ConsumeReasonModel;
use Think\Log;
use Think\Upload;
use Vendor\Sms\Sms;

/**
 *
 */
class SuiteCommentController extends AdminController {
	const STATUS_DELETED  = -1;
	const STATUS_DISABLED = 0;
	const STATUS_OK       = 1;

	const MODEL_NAME = "SuiteComment";

	/**
	 * 套餐评价评价管理首页
	 *
	 */
	public function index() {
		$this->assign("meta_title", "套餐评价评价管理");
		$action = I("get.type");
		$this->assign("action", $action);
		$db  = M(self::MODEL_NAME);
		$pre = C("DB_PREFIX");
		if (I('get.type') == 'ajax') {
			echo json_encode(array (
				                 "data" => $db->alias("c")->field("c.id,s.title,c.ctime,c.content,c.stars,c.status")
				                              ->join("{$pre}suite s on s.id=c.s_id")
				                              ->where("c.status > " . self::STATUS_DELETED)
				                              ->order("c.status,c.ctime desc")
				                              ->select()
			                 ));
		} else if (I('get.type') == 'add') {

			if (IS_GET) {
				$this->assign("meta_title", "新建套餐评价");
				$this->display("add");
			} else if (IS_POST) {
				S('C_DB_SUITE', null);
				$data          = I("post.");
				$data['ctime'] = time();
				unset($data['id']);
				/* 上传文件 */
				$config = C('PICTURE_UPLOAD');
				$Upload = new Upload($config);
				$info   = $Upload->upload();
				if (isset($info['pic'])) { //文件上传成
					$path        = substr($config['rootPath'], 1) . $info['pic']['savepath'] . $info['pic']['savename'];    //在模板里的url路径
					$data['pic'] = $path;
				}

				if ($db->add($data)) {
					$this->success("添加成功", U('index'));
				} else {
					$this->error(M()->getDbError());
				};
			}
		} else if (I('get.type') == 'edit') {
			if (IS_GET) {
				$this->assign("meta_title", "编辑套餐评价");
				$this->assign("data", $db->find(I('get.id')));
				$this->display("add");
			} else if (IS_POST) {
				$data = I("post.");
				if ($db->where(array ('id' => $data['id']))->save($data) !== false) {
					$this->success("修改成功", U('index'));
				} else {
					$this->error($db->getDbError());
				};
			}
		} else {
			$this->display();
		}
	}

	public function del() {
		$ids = I("get.ids");
		if (!$ids) {
			$this->error("请选择删除的条目");
		}
		$ids = explode(",", $ids);
		if (count($ids) <= 0) {
			$this->error("请选择删除的条目");
		}
		S('C_DB_SUITE', null);
		$res = M(self::MODEL_NAME)->where(array ("id" => array ("IN", $ids)))->setField('status', self::STATUS_DELETED);
		if ($res) {
			$loginuser = session("user_auth");
			addUserLog("删除套餐评价{$res['id']{$res['title']}}", $loginuser['uid']);
			$this->success("删除成功!");
		} else {
			$this->error(M()->getDbError());
		}
	}

	public function allow() {
		$ids = I("get.ids");
		if (!$ids) {
			$this->error("请选择通过的条目");
		}
		$ids = explode(",", $ids);
		if (count($ids) <= 0) {
			$this->error("请选择通过的条目");
		}
		$res = M(self::MODEL_NAME)->where(array ("id" => array ("IN", $ids)))->setField('status', self::STATUS_OK);
		if ($res) {
			$loginuser = session("user_auth");
			addUserLog("通过套餐评价{$res['id']{$res['title']}}", $loginuser['uid']);
			$this->success("操作成功!");
		} else {
			$this->error(M()->getDbError());
		}
	}

	
}