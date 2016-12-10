<?php
namespace Home\Controller;

use Common\Api\GatApi;

/**
 * 跳转关爱健康商城
 */
class MallController extends HomeController {
	
	/**
	 * 跳转到关爱健康商城
	 */
	public function forwardSite() {
		if(!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		} else {
			$user = session("user_auth");
			$id = $user['id'];

			$white_list_single = array (
				//航天内部+关爱通会员
				13062, 6431, 4031, 7511, 4180, 3999,22553,22546,22547,
				//上海动力所
				7084,7085,7101,14571,16101,16103,16104,20441,20495,21172,22577,
				//湖州
				16098,16099,16100,16545,21025,
				//新会员
				27715
			);

			if(in_array($id, $white_list_single) || ($id >= 22650 && $id <= 23059 ) || ($id >= 19984 && $id <= 20430)) {
				$this->display();
			} else {
				$this->redirect('/Home/Shop/index');
			}
			
		}
	}

	public function transfer() {
		if(!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		} else {
			$user = session("user_auth");
			$id = $user['id'];
			$auth_result = GatApi::get_auth_code($user['id']);
			if($auth_result['error_code']) {
				$this->error('服务器发生错误! '.$auth_result['error_message'], U('/Home/Index'));
			}
			GatApi::login($auth_result['auth_code']);
		}
	}
}