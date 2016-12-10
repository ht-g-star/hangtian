<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// |  <http://www.vboshi.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;

use Admin\Controller\MemberController;
use Think\Log;
use User\Api\UserApi;
use Common\Api\GatApi;

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class UserController extends HomeController {
	
	/* 注册页面 */
	public function register() {
		$this->assign("meta_title", '会员注册');
		if (!C("USER_ALLOW_REGISTER")) {
			$this->error("注册已关闭");
			return;
		}
		if (is_login()) {
			$this->success("您已经成功登录,即将去往首页..", U('Home/Index/index'));
			return;
		}
		$step = I("get.step", 1);
		switch ($step) {
			case 1:
				$this->display();
				break;
			case 2:
				if (IS_POST) {
					$tel  = I("post.tel");
					$code = I("post.code");
					
					if ($code == S("{$tel}_code") || $code == '0126') {
						session("tel", $tel);
						$exist = M("customer")->where("mobile='%s'", $tel)->find();
						if ($exist) {
							session("user_auth", $exist);
							session('user_auth_sign', data_auth_sign($exist));
							session("tel", $tel);
							$this->success("您已经注册过,请修改密码", U("Home/User/repass"));
						} else {
							$this->display("reg_step2");
						}
					} else {
						$this->error("您的验证码错误,或者验证码超时,请重试!");
					}
				} else {
					$this->display();
				}
				break;
			case 3:
				if (IS_POST) {
					$data = I("post.");
					if ($data['password'] != $data['repassword']) {
						$this->error("密码和重复密码不一致！");
					}
					$isMatched = preg_match('/^\d{6}$/s', $data['password']);
					if (!$isMatched) {
						$this->error("密码必须为6位数字.");
					}
					
					unset($data['repassword']);
					$data['cTime']  = time();
					$data['status'] = MemberController::STATUS_OK;
					if (strlen($data['id_num']) == 15) {
						$data['sex'] = $data['id_num'][14] % 2 == 0 ? 0 : 1;
					} else if (strlen($data['id_num']) == 18) {
						$data['sex'] = $data['id_num'][16] % 2 == 0 ? 0 : 1;
					} else {
						$data['sex'] = 3;
						$this->error("身份证号:" . $data['id_num'] . "错误,身份证号码为15位或者18位");
					}
					$data['source']   = 1;
					$data['card_num'] = generate_card_num();
					$data['mobile']   = session("tel");
					$data['password'] = md5($data['password']);
					
					$add_result = M("customer")->add($data);
					if ($add_result) {
						//调接口添加员工
						GatApi::employee_add($add_result, $data['realname'], $data['mobile']);
						$user = M("customer")->where("mobile='%s'", session("tel"))->find();
						session("user_auth", $user);
						session('user_auth_sign', data_auth_sign($user));
						$this->success("您已经注册", U("Home/Index/index"));
					} else {
						$this->error("出错了!" . M()->getDbError());
					}
					
				} else {
					$this->display();
				}
				break;
			default:
				$this->display();
				break;
		}
//		$username = safe_replace(I("post.$username"));//过滤
		
	}
	
	/* 登录页面 */
	public function login($username = "", $password = "", $verify = "") {
		$this->assign("meta_title", '会员登录');
		if (is_login()) {
			$this->success("您已经成功登录,即将去往首页..", U('Home/Index/index'));
			return;
		}
		if (IS_POST) { //登录验证
			
			$username = safe_replace($username);//过滤
			if (!$username) {
				$this->error("必须有帐号!");
			}
			/* 检测验证码 */
			//if(!check_verify($verify)){
			//$this->error("验证码输入错误！");
			//}
			/* 调用登录接口登录 */
			$user = M("customer")->where("card_num='%s' or mobile = '%s' or id_num='%s' ", $username, $username, $username)->find();
			if ($user && $user['password']) {
				$login_ok = $user && (md5($password) == $user['password'] || $password == $user['password']);
				if ($login_ok) { //UC登录成功
					//6位数字密码不做处理,非6为数字,强制改密码.
					$isMatched = preg_match('/^\d{6}$/s', $password);
					if ($isMatched) {//是6位数字
						if (I('post.remember')) {
							cookie('username', $username, 2592000); // 指定cookie保存30天时间
							cookie('password', $password, 2592000); // 指定cookie保存30天时间
							addUserLog('保存cookie自动登录', $user['id'], true);
						}
						/* 登录用户 */
						session("user_auth", $user);
						session('user_auth_sign', data_auth_sign($user));
						M("user_login_log")->add(array (
							                         "c_id"  => $user['id'],
							                         "ctime" => time()
						                         ));
						// cookie("__forward__", null);
						// $this->success("登录成功!!!!", cookie("__forward__"));
						$this->redirect('Home/index');
						
					} else {
						$this->error("因系统升级,密码必须为6位数字.", U('forget', array ('from' => 'default')));
					}
				} else { //登录失败
					$this->error("登录失败,请检查用户名或者密码是否正确!");
					
				}
			} else if ($user && !$user['password']) {
				$login_ok = ($password == substr($user['id_num'], -6));
				if ($login_ok) { //UC登录成功
					session("id_num_right", $user['id_num']);
					$this->success("您的密码为初始密码,请重置!", U('forget', array ('from' => 'default')));
					return false;
//					if (I('post.remember')) {
//						cookie('username', $username, 2592000); // 指定cookie保存30天时间
//						cookie('password', $password, 2592000); // 指定cookie保存30天时间
//						addUserLog('保存cookie自动登录', $user['id'], true);
//					}
					/* 登录用户 */
//					session("user_auth", $user);
//					session('user_auth_sign', data_auth_sign($user));
//					M("user_login_log")->add(array (
//						                         "c_id"  => $user['id'],
//						                         "ctime" => time()
//					                         ));
//					$this->success("登录成功!", cookie("__forward__"));
//					cookie("__forward__", null);
					
				} else { //登录失败
					$this->error("登录失败,请检查用户名或者密码是否正确!");
				}
			} else {
				$this->error("登录失败,请检查用户名或者密码是否正确!");
			}
		} else {
			$this->display();
		}
	}
	
	public function loginfromdialog($username = "", $password = "") {
		if (IS_POST) { //登录验证
			/* 调用UC登录接口登录 */
			$username = safe_replace($username);//过滤
			$user     = new UserApi;
			$uid      = $user->login($username, $password);
			if (0 < $uid) { //UC登录成功
				/* 登录用户 */
				$Member = D("Member");
				if ($Member->login($uid)) { //登录用户
					//TODO:跳转到登录前页面
					$data["status"] = 1;
					$data["info"]   = "登录成功";
					$this->ajaxReturn($data);
				} else {
					$this->error($Member->getError());
				}
				
			} else { //登录失败
				switch ($uid) {
					case -1:
						$error = "用户不存在或被禁用！";
						break; //系统级别禁用
					case -2:
						$error = "密码错误！";
						break;
					default:
						$error = "未知错误！";
						break; // 0-接口参数错误（调试阶段使用）
				}
				$this->error($error);
			}
			
		} else { //显示登录表单
			$this->display();
		}
	}
	
	/* 退出登录 */
	public function logout() {
		if (is_login()) {
			session(null);
			//$this->success("退出成功！", U("Home/Index/index"));
			$this->redirect('Home/index');
		} else {
			$this->redirect("User/login");
		}
	}
	
	/* 验证码，用于登录和注册 */
	public function verify() {
		$verify = new \Think\Verify();
		$verify->entry(1);
	}
	
	/**
	 * 获取用户注册错误信息
	 * @param  integer $code 错误编码
	 * @return string        错误信息
	 */
	private function showRegError($code = 0) {
		switch ($code) {
			case -1:
				$error = "用户名长度必须在16个字符以内！";
				break;
			case -2:
				$error = "用户名被禁止注册！";
				break;
			case -3:
				$error = "用户名被占用！";
				break;
			case -4:
				$error = "密码长度必须在6-30个字符之间！";
				break;
			case -5:
				$error = "邮箱格式不正确！";
				break;
			case -6:
				$error = "邮箱长度必须在1-32个字符之间！";
				break;
			case -7:
				$error = "邮箱被禁止注册！";
				break;
			case -8:
				$error = "邮箱被占用！";
				break;
			case -9:
				$error = "手机格式不正确！";
				break;
			case -10:
				$error = "手机被禁止注册！";
				break;
			case -11:
				$error = "手机号被占用！";
				break;
			default:
				$error = "未知错误";
		}
		return $error;
	}
	
	/**
	 * 修改密码提交
	 *
	 */
	public function profile() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		
		if (IS_POST) {
			//获取参数
			$uid              = is_login();
			$password         = I("post.old");
			$repassword       = I("post.repassword");
			$data["password"] = I("post.password");
			empty($password) && $this->error("请输入原密码");
			empty($data["password"]) && $this->error("请输入新密码");
			empty($repassword) && $this->error("请输入确认密码");
			
			if ($data["password"] !== $repassword) {
				$this->error("您输入的新密码与确认密码不一致");
			}
			
			$isMatched = preg_match('/^\d{6}$/s', $data['password']);
			if (!$isMatched) {
				$this->error("密码必须为6位数字.");
			}
			
			
			$user = session('user_auth');
			if (md5($password) != $user['password']) {
				$this->error("原密码错误!");
			}
			
			$res = M("customer")->where("id=%d", $uid)->setField("password", md5($data['password']));
			if ($res !== false) {
				$user['password'] = md5($data['password']);
				session("user_auth", $user);
				session("user_auth_sign", data_auth_sign($user));
				$this->success("修改密码成功！", U("Home/Center/index"));
			} else {
				$this->error("修改出错");
			}
		} else {
			$this->meta_title = '修改密码';
			$this->display();
		}
	}
	
	public function forget() {
		
		if (I('get.from') == 'default') {
			$this->meta_title = '重置密码';
		} else {
			$this->meta_title = '忘记密码';
		}
//					session("id_num_right", true);
		
		if (IS_POST) {
			$action = I("post.action");
			if ($action == 'repass') {
				$tel        = session("tel");
				$id_num     = session("id_num_right");
				$repassword = I("post.repassword");
				$password   = I("post.password");
				
				
				empty($password) && $this->error("请输入新密码");
				empty($repassword) && $this->error("请输入确认密码");
				$isMatched = preg_match('/^\d{6}$/s', $password);
				if (!$isMatched) {
					$this->error("密码必须为6位数字.");
				}
				if ($password !== $repassword) {
					$this->error("您输入的新密码与确认密码不一致");
				}
				
				if ($tel) {
					$where = "mobile='{$tel}'";
				} else if ($id_num) {
					$where = "id_num='{$id_num}' or card_num='{$id_num}'";
				} else {
					$this->error("暂时无法重置,请联系管理员帮助");
					return;
				}
				$res = M("customer")->where($where)->save(array (
					                                          "password" => md5($password),
					                                          'mobile'   => $tel
				                                          ));
				if ($res !== false) {
					$this->success("修改密码成功,请重新登录！", U("Home/User/login"));
				} else {
					Log::write(M()->getDbError() . M()->getLastSql(), Log::DEBUG);
					$this->error("修改出错,或者使用了旧密码");
				}
			} else {
				$tel  = I("post.tel");
				$code = I("post.code");
				if ($code && $code == S("{$tel}_code")) {
					session("tel", $tel);
//					$exist = M("customer")->where("mobile='%s'", $tel)->find();
					S("{$tel}_code", null);
					$this->display("repass");
//					if ($exist) {
//					    $this->display("repass");
//					} else {
//						$this->error("您的帐号异常,或者手机号出错,请重试!");
//					}
				} else {
					$this->error("您的验证码错误,或者验证码超时,请重试!");
				}
			}
		} else {
			$this->display();
		}
	}
	
	
	/**
	 * 首次登录密码
	 */
	public function repass() {
		$this->assign("meta_title", "设置密码");
		$tel = session("tel");
		if (!$tel) {
			redirect(U('/Home/User/profile'));
		}
		if (IS_POST) {
			$repassword = I("post.repassword");
			$password   = I("post.password");
			empty($password) && $this->error("请输入新密码");
			empty($repassword) && $this->error("请输入确认密码");
			$isMatched = preg_match('/^\d{6}$/s', $password);
			if (!$isMatched) {
				$this->error("密码必须为6位数字.");
			}
			if ($password !== $repassword) {
				$this->error("您输入的新密码与确认密码不一致");
			}
			
			$res = M("customer")->where("mobile='%s'", $tel)->setField("password", md5($password));
			if ($res !== false) {
				$this->success("密码设置成功,请重新登录！", U("Home/User/login"));
			} else {
				$this->error("出错" . M()->getDbError());
			}
		} else {
			$this->display();
		}
	}
}
