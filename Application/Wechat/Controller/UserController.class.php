<?php
namespace Wechat\Controller;

use Org\Wechat\TPWechat;
use Org\Wechat\Wechat;
use Think\Controller;
use Think\Log;

class UserController extends FollowerController {

	public function _initialize() {
		parent::_initialize();
		$this->assign("meta_title", "会员中心");
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

		$this->openid = get_openid(I('openid'));
		$this->user = session('user_auth');

		// Log::write("当前用户：".json_encode(session('user_auth')));

		// if(empty($this->user["openid"]) || $this->user["openid"] != $this->openid){
		// 	$tmp = M("customer")->find($this->user["id"]);
		// 	$tmp["openid"] = $this->openid;
		// 	M("customer")->save($tmp);
		// 	session('user_auth', $tmp);
		// }
		
		// if (empty($this->user['password'])) {
		// 	// $this->error("您没有设置密码,请先设置密码", U('Public/forget', array ('from' => 'default')));
		// }
	}

	public function index() {
		$this->user=$this->get_login_user();
		$this->display();
	}

	public function info() {
		$this->display();
	}

	public function address() {
		$this->assign("meta_title", "收货地址");

		$login     = session("user_auth");
		$addresses = M("address")->where("uid=%d", $login['id'])->select();
		$this->assign('addresses', $addresses);

		$this->assign("right_btn", array (
			"url"   => U('User/add_address'),
			'class' => "am-icon-plus"
		));
		$this->display();
	}

	public function set_address_id($aid) {
		$user               = session("user_auth");
		$user['address_id'] = $aid;
		session("user_auth", $user);
		echo M('customer')->where("id=%d", $user['id'])->setField("address_id", $aid);
	}

	public function add_address() {
		$this->assign("meta_title", "新增收货地址");
		if (IS_POST) {
			$address             = D('Home/address');
			$data                = I('post.');
			$data['address']     = I('post.address', '', 'strip_tags');//$_POST["posi"];
			$data['cellphone']   = I('post.cellphone', 0, 'intval'); // 用intval过滤$_POST["pho"];
			$data['realname']    = I('post.realname', '', 'strip_tags'); // 用strip_tags过滤$_POST["rel"];
			$data['address']     = safe_replace($data['address']);//过滤
			$data['realname']    = safe_replace($data['realname']);//过滤
			$data['status']      = 0;
			$data['uid']         = $this->user['id'];
			$data['create_time'] = NOW_TIME;

			$address = D('Home/address');
			$result  = $address->update();

			if ($data['default'] == 'on') {
				$this->user['address_id'] = $result;
				session("user_auth", $this->user);
				M("customer")->where("id=%d", $this->user['id'])->setField("address_id", $result);
			}
			if ($result !== false) {
				if (cookie("__forward__")) {
					$this->success("成功", U('Shopcart/index'));
				} else {
					$this->success("成功", U('address'));
				}

			} else {
				$this->error("错误" . $address->getDbError());
			}
		} else {
			$province = M("area")->where("pid=0")->select();
			$this->assign("province", $province);
			$this->display("edit_address");
		}
	}

	public function edit_address() {
		$this->assign("meta_title", "收货地址");
		if (IS_POST) {
			$data   = I('post.');
			$db     = D('Home/address');
			$result = $db->where("id=%d", $data['id'])->save($data);
			if ($result !== false) {

				if ($data['default'] == 'on') {
					$this->user['address_id'] = $result;
					session("user_auth", $this->user);
					M("customer")->where("id=%d", $this->user['id'])->setField("address_id", $result);
				}

				$this->success("更新成功!", U("address"));
			} else {
				$this->error("错误!" . $db->getDbError());
			}
		} else {
			$aid     = I("get.aid");
			$address = D('Home/address')->find($aid);
			if ($address) {
				$province = M("area")->where("pid=0")->select();
				$this->assign("province", $province);
				$this->assign("data", $address);
			} else {
				$this->error("错误访问!");
			}
			$this->display("edit_address");

		}
	}

	public function del_address($aid) {
		$db = M("address");
		if ($aid == $this->user['address_id']) {
			$this->error("默认地址不能删除!");
			return false;
		}
		$result = $db->where("id=%d", $aid)->delete();
		if ($result !== false) {
			$this->success("删除成功!");
		} else {
			$this->error("失败!" . $db->getDbError());
		}
	}

	public function qr_code() {
		$this->assign("开始体检");
		$this->assign("user", $this->user);
		$this->display();
	}
}



