<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;
/**
 * 签到模型控制器
 * 文档模型列表和详情
 */
class SignController extends HomeController {
	public function index() {
		/*****用户中心签到****/
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid     = is_login();
		$Sign    = D("Sign");
		$nowtime = NOW_TIME;
		$d       = date('H:i:s', $nowtime);
		$member  = M("customer"); // 实例化对象
		$c       = isSigned($uid);
		if (!$c) {/*签过到*/
			/*新增签到*/
			$data['uid']         = $uid;
			$data['status']      = "1";
			$data['create_time'] = NOW_TIME;
			if ($Sign->add($data)) {
				$member->where("id='$uid'")->setInc('score', C("SIGN_SCORE")); // 用户的积分加10
				$data['score'] = $member->where("id='$uid'")->getField('score');
				$data['msg']   = "已签到，积分+" . C("SIGN_SCORE") . ",签到时间：" . $d;
				addUserLog("用户" . $data['msg'], $uid, true);
				$data['status'] = "1";
				$this->ajaxreturn($data);
			}

//			/*首次签到*/
//			$data['uid']         = $uid;
//			$data['status']      = "1";
//			$data['create_time'] = NOW_TIME;
//			$member->where("id='$uid'")->setInc('score', C("SIGN_SCORE")); // 用户的积分加10
//			if ($Sign->add($data)) {
//				$data['score'] = $member->where("id='$uid'")->getField('score');
//				$this->ajaxreturn($data);
//			}
//		}

		} else {
			/*签过到*/
			$data['status'] = "0";
			$data['msg']    = "今天" . date("Y-m-d H:i:s", $c['create_time']) . "已签过到";
			$data['score']  = $member->where("id='$uid'")->getField('score');
			$this->ajaxreturn($data);

		}

	}
}
