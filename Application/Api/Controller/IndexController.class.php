<?php
namespace Api\Controller;

use Think\Controller;

class IndexController extends Controller {
	protected $data;

	public function _initialize() {

		parent::_initialize();
		/* 读取数据库中的配置 */
		$config = S('DB_CONFIG_DATA');
		if (!$config) {
			$config = api('Config/lists');
			S('DB_CONFIG_DATA', $config);
		}
		C($config); //添加配置

		//认证API
		$this->auth();
		$this->data = $this->getData();
	}

	public function index() {
		$this->ajaxReturn(array (
			                  "err" => 0,
			                  "msg" => "接口畅通!",
		                  ));
	}

	public function __call($method, $args) {
		$this->ajaxReturn(array (
			                  "err" => 1,
			                  "msg" => "不存在该调用方法!",
		                  ));
	}

	protected function getData() {
		return json_decode(file_get_contents("php://input"), true);
	}

	protected function auth() {
		$ip_list = trim(C('ALLOW_API_LIST'));
		$ip_list = explode("|", $ip_list);
		if (count($ip_list) < 1) {
			$this->ajaxReturn(array (
				                  "err" => 1,
				                  "msg" => "未配置允许IP列表!",
			                  ));
		}
		if (!in_array(get_client_ip(), $ip_list)) {
			$this->ajaxReturn(array (
				                  "err" => 1,
				                  "msg" => "未授权的访问!",
			                  ));
		}
	}
}