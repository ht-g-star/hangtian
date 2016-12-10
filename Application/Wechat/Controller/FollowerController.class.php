<?php
/**
 * 粉丝
 * User: litao
 * Date: 15/12/19
 * Time: 09:51
 */

namespace Wechat\Controller;

use Org\Wechat\TPWechat;
use Org\Wechat\Wechat;
use Think\Controller;
use Think\Log;

class FollowerController extends Controller {
	protected $openid;
	protected $user;
	
	protected function _initialize() {
		$config = S('DB_CONFIG_DATA');
		if (!$config) {
			$config = api('Config/lists');
			S('DB_CONFIG_DATA', $config);
		}
		C($config); //添加配置
		$option       = array (
			'token'     => C("WX_TOKEN"), //填写你设定的key
			'appid'     => C("WX_APPID"), //填写高级调用功能的app id
			'appsecret' => C('WX_APPSCRETE'), //填写高级调用功能的密钥
			"debug"     => false, "logcallback" => 'log_wechat'
		);
		$wx           = new  TPWechat($option);
		$this->openid = get_openid();

		$no_need_user = array ('Public', 'Mall', 'Report');
		if (!in_array(CONTROLLER_NAME, $no_need_user)) {
			$this->user = $this->get_login_user();
		}
		$user_info = S($this->openid . "_info");
		if (!$user_info) {
			$user_info = $wx->getUserInfo($this->openid);
			S($this->openid . "_info", $user_info);
		}
		$this->assign("follower", $user_info);
		$this->assign("_user",$this->user);
	}

	protected function get_login_user() {
		Log::write("OPENID是：".$this->openid);
		if (session("user_auth")) {
			$this->user = M('customer')->where("openid='%s'", $this->openid)->find();
			session("user_auth", $this->user);
			return $this->user;
		} else {
			$this->user = M('customer')->where("openid='%s'", $this->openid)->find();
			if (!$this->user) {
				$this->redirect('Public/login');
			} else {
				session("user_auth", $this->user);
			}
			return $this->user;
		}

	}

	/**
	 * Get请求获取JSON 转Array
	 * @param $url 请求地址(附带参数)
	 * @param return 数组对象
	 */
	protected function getJsonByUrl($url){
		$result=file_get_contents($url);
		return json_decode($result,true); 
	}
	
	protected function getJsonByUrl1($url){
		$result=@file_get_contents($url);
		$result = iconv("GBK", "utf-8//IGNORE",$result);
		return $result; 
	}

}