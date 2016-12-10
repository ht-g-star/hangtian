<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Think\Upload;
use User\Api\UserApi;

/**
 * 后台用户控制器
 *
 */
class CardreaderController extends AdminController {

	/**
	 * 读卡器配置
	 *
	 */
	public function index() {
		$this->meta_title = '读卡器配置';
		$this->display();
	}
	
	
}