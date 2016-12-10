<?php

namespace Addons\SharePage;

use Common\Controller\Addon;

/**
 * 分享插件
 * @author Todd
 */
class SharePageAddon extends Addon {

	public $info
		= array (
			'name'        => 'SharePage',
			'title'       => '分享',
			'description' => '分享页面',
			'status'      => 1,
			'author'      => 'Todd',
			'version'     => '0.1'
		);

	public function install() {
		return true;
	}

	public function uninstall() {
		return true;
	}

	//实现的SharePage钩子方法
	public function SharePage($param) {
		$this->assign("param", $param);
		$this->display("share");
	}

}