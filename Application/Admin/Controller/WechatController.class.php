<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Org\Wechat\TPWechat;
use Org\Wechat\Wechat;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 *
 */
class WechatController extends AdminController {

	/**
	 * 设置首页
	 *
	 */
	public function index() {
		$this->assign("meta_title", "公众号设置");
		if (IS_POST) {
			$config = I("post.config");
			if ($config && is_array($config)) {
				$Config = M('Config');
				foreach ($config as $name => $value) {
					$map = array ('name' => $name);
					$Config->where($map)->setField('value', $value);
					S('DB_CONFIG_DATA',null);
				}
			}
			$this->success('保存成功！');
		} else {
			$config = M("config")->where(array (
				                             "group" => 10
			                             ))->select();
			$this->assign("configs", $config);
			$this->display();
		}
	}

	/**
	 * 菜单管理
	 */
	public function menu() {
		$this->assign("meta_title", "自定义菜单");
		if (IS_POST) {
			$data = I("post.");
			F('menu', serialize($data));
			$this->success("保存成功，点击发送24小时内即可生效");
		} else {
			$button = unserialize(F("menu"));
			$this->assign("button", $button['button']);
			$this->display();
		}
	}

	/**
	 * 自动回复
	 */
	public function reply() {
		$this->assign("meta_title", "自动回复");
		$db=M("wx_reply");
		$data=$db->order("sort DESC,id DESC")->select();
		$this->assign("_list",$data);
		$this->display();
	}

	public function add() {
		$this->assign("meta_title", "自动回复");
		if(IS_POST){
			$db=M("wx_reply");
			$data=I("post.");
			if ($_FILES['pic']['size']) {
				$info            = $this->upload();
				$data['pic'] = './Uploads/' . $info['pic']['savepath'] . $info['pic']['savename'];
			}
			$ok=$db->add($data);
			if($ok){
				$this->success("增加成功！",U('reply'));
			}else{
				$this->error("出错，请检查是否填写完整！");
			}
		}else{
			$this->display();
		}

	}


	public function edit() {
		$id = I('id', 0, 'intval');
		$db=M("wx_reply");
		$this->assign("v", $db->find($id));
		if (IS_POST) {
			$data = I("post.");
			if ($_FILES['pic']['size']) {
				$info            = $this->upload();
				$data['pic'] = './Uploads/' . $info['pic']['savepath'] . $info['pic']['savename'];

			}
			$ok = $db->where('id=' . $id)->save($data);
			if ($ok) {
				$this->success("成功!", U('reply'));
			}
			else {
				$this->error("失败!");
			}
		}
		else {
			$this->display("add");
		}
	}

	public function del() {
		$id = I('get.id', 0, 'intval');
		$db=M("wx_reply");
		$ok = $db->where('id=' . $id)->delete();
		if ($ok) {
			$this->success("成功!", U('reply'));
		}
		else {
			$this->error("失败!");
		}
	}
	private function upload() {
		$config = array(
			'maxSize'  => 3145728,
			'rootPath' => './Uploads/',
			'savePath' => '',
			'saveName' => array( 'uniqid', '' ),
			'exts'     => array( 'jpg', 'gif', 'png', 'jpeg' ),
			'autoSub'  => true,
			'subName'  => array( 'date', 'Ymd' ),
		);
		$upload = new \Think\Upload($config);// 实例化上传类

		// 上传文件
		$info = $upload->upload();
		if (!$info) {// 上传错误提示错误信息
			$this->error($upload->getError());
		}
		else {// 上传成功
			return $info;
		}
	}


	// 发送菜单到微信
	public function send() {
		$data = unserialize(F("menu"));
		$new  = array ();
		foreach ($data['button'] as $key => $but) {
			if ($but['type'] != 'sub') {//无子菜单
				unset($but['sub']);
				if (empty($but['name'])) {
					continue;
				}
				if (empty($but['value'])) {
					$but['value'] = $but['name'];
				}
				if ($but['type'] == 'click') {
					$but['key'] = $but['value'];
				} else if ($but['type'] == 'view') {
					$but['url'] = $but['value'];
				} else {
					$but['key'] = $but['value'];
				}
				unset($but['value']);
			} else {//有子菜单
				$but['sub_button'] = $but['sub'];
				unset($but['type']);
				unset($but['sub']);
				if (empty($but['name'])) {
					continue;
				}
				foreach ($but['sub_button'] as $key2 => &$b) {
					if (empty($b['name'])) {
						unset($but['sub_button'][ $key2 ]);
						continue;
					}
					if (empty($b['value'])) {
						$b['value'] = $b['name'];
					}
					if ($b['type'] == 'click') {
						$b['key'] = $b['value'];
					} else if ($b['type'] == 'view') {
						$b['url'] = $b['value'];
					} else {
						$b['key'] = $b['value'];
					}
					unset($b['value']);
				}

			}
			$new['button'][ $key - 1 ] = $but;
		}

		$data     = $new;
		$new_data = array ();
		foreach ($data['button'] as $but) {
			if ($but['sub_button']) {
				$temp = array ();
				foreach ($but['sub_button'] as $ss) {
					$temp[] = $ss;
				}
				$but['sub_button'] = $temp;
			}
			$new_data['button'][] = $but;
		}


		$option = array (
			'token'     => C("WX_TOKEN"), //填写你设定的key
			'appid'     => C("WX_APPID"), //填写高级调用功能的app id
			'appsecret' => C('WX_APPSCRETE'), //填写高级调用功能的密钥
			"debug"     => false, "logcallback" => 'log_wechat'
		);
		$wx     = new TPWechat($option);
		$result = $wx->createMenu($new_data);
		if ($result) {
			$this->success("创建成功");
		} else {
			$this->error("创建失败,请检查是否遗漏?" . $wx->errMsg);
		}
	}


}
