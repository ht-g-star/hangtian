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
 * 后台用户控制器
 *
 */
class MemberTrashController extends AdminController {

	/**
	 * 回收站
	 */
	public function index() {
		$db = M('customer');
		if (I('get.type') == 'ajax') {
			$data = $db->field('id,card_num,card_sn,company,dept,position,is_onsite,realname,sex,id_num,mobile,balance,rank,status')
			           ->where(array ('status' => MemberController::STATUS_DELETED))
			           ->select();
			if (empty($data)) {
				$data = array ();
			}
			echo json_encode(array (
				                 "data" => $data,
			                 ));
		} else {
			$this->assign("meta_title", "会员管理");
			$this->display();
		}
	}


	public function recycle() {
		if (IS_GET) {
			$ids = I("get.ids");
			if (!$ids) {
				$this->error("参数出错");
			}
			$db     = M("customer");
			$result = $db->where("id in ({$ids})")->save(array (
				                                             "status"   => MemberController::STATUS_NO_CARD,
				                                             "card_num" => "",
				                                             "card_sn"  => ""
			                                             ));
			if ($result !== false) {
				$loginuser = session("user_auth");
				addUserLog("恢复会员{$ids}", $loginuser['uid']);
				$this->success("恢复成功");
			} else {
				$this->error("保存出错!" . $db->getError());

			}

		}
	}

	public function del() {
		if (IS_GET) {
			$ids = I("get.ids");
			if (!$ids) {
				$this->error("参数出错");
			}
			$db     = M("customer");
			$result = $db->where("id in ({$ids})")->delete();
			if ($result !== false) {
				$loginuser = session("user_auth");
				addUserLog("删除会员{$ids}", $loginuser['uid']);
				$this->success("删除成功!");
			} else {
				$this->error("删除出错!" . $db->getError());

			}

		}
	}
	
}