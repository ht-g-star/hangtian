<?php
namespace Wechat\Controller;

use Admin\Controller\MemberController;
use Org\Wechat\TPWechat;
use Org\Wechat\Wechat;
use Think\Controller;
use Think\Log;


class PublicController extends FollowerController {
	protected function _initialize() {
		parent::_initialize();
		
	}
	
	public function login() {
		$this->assign("meta_title", '欢迎登录');
//		if (session("user_auth")) {
//			$this->redirect("User/index");
//		} else {
		if (IS_POST) {
			$usn                             = I('post.tel');
			$pwd                             = I("post.password");
			$where['mobile|card_num|id_num'] = $usn;
			$db                              = M("customer");
			$exist                           = $db->where($where)->find();
			if ($exist['password']) {
				if (md5($pwd) == $exist['password']) {
					
					$isMatched = preg_match('/^\d{6}$/s', $pwd);
					if ($isMatched) {//是6位数字
						$db->where("id=%d", $exist['id'])->setField("openid", $this->openid);
						$exist['openid'] = $this->openid;
						session('user_auth', $exist);
						$this->success('登录成功!', U('User/index'));
					} else {
						$this->error("因系统升级,密码必须为6位数字.", U('forget', array ('from' => 'default')));
					}
					
				} else {
					$this->error("帐号或者密码错误!");
				}
			} else {
				if ($pwd == substr($exist['id_num'], -6)) {
					$this->error("您的密码为初始密码,请重置!", U('forget', array ('from' => 'default')));
				} else {
					$this->error("帐号或者密码错误!");
				}
			}
		} else {
			$this->user = M('customer')->where("openid='%s'", $this->openid)->find();
			if ($this->user) {
				$this->get_login_user();
				redirect($_SERVER['HTTP_REFERER']);
			}
			$this->display();
		}
//		}
		
	}
	
	/* 注册页面 */
	public function register() {
		$this->assign("meta_title", '会员注册');
		if (!C("USER_ALLOW_REGISTER")) {
			$this->error("注册已关闭");
			return;
		}
		if (is_login()) {
			$this->success("您已经成功登录,即将去往首页..", U('User/index'));
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
					
					if ($code && $code == S("{$tel}_code")) {
						session("tel", $tel);
						$exist = M("customer")->where("mobile='%s'", $tel)->find();
						if ($exist) {
							$exist['openid'] = get_openid();
							session("user_auth", $exist);
							session('user_auth_sign', data_auth_sign($exist));
							M('customer')->where("mobile='%s'", $tel)->setField("openid", get_openid());
							$this->success("您已经注册过了,帮您成功登录", U("User/index"));
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
					
					$exist = M("customer")->where("id_num='%s'", $data['id_num'])->find();
					if ($exist) {
						$this->error("身份证已经被注册过,请联系工作人员");
					}
					
					$data['source']   = 1;
					$data['card_num'] = generate_card_num();
					$data['mobile']   = session("tel");
					$data['password'] = md5($data['password']);
					$data['openid']   = get_openid();
					$add_result       = M("customer")->add($data);
					if ($add_result) {
						$user = M("customer")->where("mobile='%s'", session("tel"))->find();
						session("user_auth", $user);
						session('user_auth_sign', data_auth_sign($user));
						$this->success("注册成功", U("User/index"));
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
	
	public function forget() {
		if (I('from') == 'default') {
			$this->meta_title = '重置密码';
		} else {
			$this->meta_title = '忘记密码';
		}
		
		if (IS_POST) {
			$action = I("post.action");
			if ($action == 'repass') {
				$tel        = session("tel");
				$repassword = I("post.repassword");
				$password   = I("post.password");
				empty($password) && $this->error("请输入新密码");
				empty($repassword) && $this->error("请输入确认密码");
				
				if ($password !== $repassword) {
					$this->error("您输入的新密码与确认密码不一致");
				}
				$isMatched = preg_match('/^\d{6}$/s', $repassword);
				if (!$isMatched) {
					$this->error("密码必须为6位数字.");
				}
				
				
				$res = M("customer")->where("mobile='%s'", $tel)->setField("password", md5($password));
				if ($res !== false) {
					$this->success("修改密码成功,请重新登录！", U("Public/login"));
				} else {
					Log::write(M()->getDbError() . M()->getLastSql(), Log::DEBUG);
					$this->error("修改出错,或者您使用了原密码作为新密码使用");
				}
			} else {
				$tel  = I("post.tel");
				$code = I("post.code");
				if ($code && $code == S("{$tel}_code")) {
					session("tel", $tel);
					$exist = M("customer")->where("mobile='%s'", $tel)->find();
					S("{$tel}_code", null);
					if ($exist) {
						$this->display("repass");
					} else {
						$this->error("您的帐号异常,或者手机号出错,请重试!");
					}
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
			redirect(U('User/index'));
		}
		if (IS_POST) {
			$repassword = I("post.repassword");
			$password   = I("post.password");
			empty($password) && $this->error("请输入新密码");
			empty($repassword) && $this->error("请输入确认密码");
			
			if ($password !== $repassword) {
				$this->error("您输入的新密码与确认密码不一致");
			}
			$isMatched = preg_match('/^\d{6}$/s', $repassword);
			if (!$isMatched) {
				$this->error("密码必须为6位数字.");
			}
			
			
			$res = M("customer")->where("mobile='%s'", $tel)->setField("password", md5($password));
			if ($res !== false) {
				$this->success("密码设置成功！", U("Public/login"));
			} else {
				$this->error("出错" . M()->getDbError());
			}
		} else {
			$this->display();
		}
	}
	
	
	public function unbind() {
		$openid = get_openid();
		$exist  = M("customer")->where("openid='%s'", $openid)->find();
		if ($exist) {
			$res = M("customer")->where("id=%d", $exist['id'])->setField("openid", '');
			if ($res) {
				session(null);
				$this->success("解绑成功,现在去登录页面");
			} else {
				$this->error("解绑失败" . M()->getDbError());
			}
		} else {
			$this->error("不存在该用户!");
		}
	}
}



