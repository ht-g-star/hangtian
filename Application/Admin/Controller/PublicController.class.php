<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Think\Log;
use User\Api\UserApi;

/**
 * 后台首页控制器
 *
 */
class PublicController extends \Think\Controller {
	
	/**
	 * 后台用户登录
	 *
	 */
	public function login($username = null, $password = null, $verify = null) {
		if (IS_POST) {
			/* 检测验证码 TODO: */
			// if(!check_verify($verify)){
			//  $this->error('验证码输入错误！');
			// }
			$username = safe_replace($username);//过滤
			
			/* 调用UC登录接口登录 */
			$User = new UserApi;
			$uid  = $User->login($username, $password);
			if (0 < $uid) { //UC登录成功
				/* 登录用户 */
				$Member = D('Member');
				if ($Member->login($uid)) { //登录用户
					//TODO:跳转到登录前页面
					$this->success('登录成功！', U('Admin/Index/index'));
				} else {
					$this->error($Member->getError());
				}
				
			} else { //登录失败
				switch ($uid) {
					case -1:
						$error = '用户不存在或被禁用！';
						break; //系统级别禁用
					case -2:
						$error = '密码错误！';
						break;
					default:
						$error = '未知错误！';
						break; // 0-接口参数错误（调试阶段使用）
				}
				$this->error($error);
			}
		} else {
			if (is_login()) {
				$this->redirect('Index/index');
			} else {
				/* 读取数据库中的配置 */
				$config = S('DB_CONFIG_DATA');
				if (!$config) {
					$config = D('Config')->lists();
					S('DB_CONFIG_DATA', $config);
				}
				C($config); //添加配置
				
				$this->display();
			}
		}
	}
	
	/* 退出登录 */
	public function logout() {
		if (is_login()) {
			D('Member')->logout();
			session('[destroy]');
			$this->success('退出成功！', U('login'));
		} else {
			$this->redirect('login');
		}
	}
	
	public function verify() {
		$verify = new \Think\Verify();
		$verify->entry(1);
	}
	
	public function express() {
		ignore_user_abort();
		session_write_close();
		set_time_limit(99999999);
		$db   = M("order");
		$list = $db->where("status=2 and send_time is not null and send_time < time() - 40 * 24 * 3600")->select();

		$shop = M("shoplist");
		foreach ($list as $row) {
			$db->where("id={$row['id']}")->setField('status', '3');
			$shop->where("orderid={$row['id']}")->setField("status", 3);
		}
	}

//
//
//	public function helpMe() {
//		/*
//		$option   = C('MEMBER_UPLOAD');
//		$savePath = $option['rootPath'] . $option['savePath'] . "back/";
//
//		if ($dh = opendir($savePath)) {
//			while (($file = readdir($dh)) !== false) {
//				if ($file == "." || $file == "..") {
//					continue;
//				}
//				$dirs[] = $file;
//			}
//			closedir($dh);
//		} else {
//			$this->error("打开目录失败" . $dh);
//		}
//		foreach ($dirs as $dir) {
//			if ($dh = opendir($savePath . $dir)) {
//				while (($file = readdir($dh)) !== false) {
//					if ($file == "." || $file == "..") {
//						continue;
//					}
//					$ext = explode(".", $file);
//					$ext = $ext[1];
//					if ($ext != "xlsx") {
//						continue;
//					}
//					$files[] = $savePath . $dir . "/" . $file;
//				}
//				closedir($dh);
//			} else {
//				$this->error("打开目录失败" . $dh);
//			}
//		}
//
//		F("files", $files);
//		*/
//
//		$files = F("files");
//		if (!empty($files)) {
//			$this->save(array_shift($files));
//		}
//		F("files", $files);
//		$this->success("继续,还有" . count($files) . "个");
//	}
//
//	private function save($path) {
//		set_time_limit(999999);
//		if (stripos($path, ".xlsx") !== false) {
//			$data = excel_import($path, 'xlsx');
//		} else {
//			$data = excel_import($path);
//		}
//		unset($data[1]);
//		$db = M("customer");
//		foreach ($data as $row) {
//			if ($row['A'] == '') {
//				continue;
//			}
//
//			if (strlen($row['F']) == 15) {
//			} else if (strlen($row['F']) == 18) {
//			} else {
//				continue;
//			}
//			$exist = $db->where("id_num=' % s'", (string)$row['F'])->find();
//			$save  = array ("mobile" => (string)$row['G']);
//			if ($exist['password']) {
//				$save["password"] = '';
//				$result           = $db->fetchSql(false)->where("id_num=' % s'", (string)$row['F'])->save($save);
//			}
//
//		}
//
//	}
}
