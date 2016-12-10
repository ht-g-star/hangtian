<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Admin\Model\ConfigModel;
use Think\Upload;
use User\Api\UserApi;

/**
 * 后台用户控制器
 *
 */
class MemberConfigController extends AdminController {

	/**
	 * 短信开关
	 *
	 */
	public function index() {

		$this->meta_title = '会员系统设置';

		$config_model = new ConfigModel();
		$config       = $config_model->getOne("SMS_SWITCH");
		if (IS_POST) {
			$value = I("post.SMS_SWITCH");
			if ($config_model->setOne("SMS_SWITCH", $value, true, '短信开关') !== false) {
				$this->success("设置成功");
			} else {
				$this->error("设置失败" . $config_model->getError());
			}
		} else {
			$this->assign("config", $config);
			$this->display();
		}

	}
	
	
}