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
use Wechat\Model\PayNotifyCallBack;

class WechatNotifyController extends Controller {

	protected function _initialize() {
		$config = S('DB_CONFIG_DATA');
		if (!$config) {
			$config = api('Config/lists');
			S('DB_CONFIG_DATA', $config);
		}
		C($config); //添加配置
		$option = array (
			'token'     => C("WX_TOKEN"), //填写你设定的key
			'appid'     => C("WX_APPID"), //填写高级调用功能的app id
			'appsecret' => C('WX_APPSCRETE'), //填写高级调用功能的密钥
			"debug"     => false, "logcallback" => 'log_wechat'
		);
//		$wx     = new  TPWechat($option);
	}

	public function index() {
		Log::write("PayBack:");
		vendor("WxpayAPI.lib.WxPay#Api");
		vendor("WxpayAPI.lib.WxPay#Notify");
		vendor('WxpayAPI.example.log');

		$notify = new PayNotifyCallBack();
		$notify->Handle(false);
	}


}