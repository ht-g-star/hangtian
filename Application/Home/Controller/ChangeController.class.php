<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
 
namespace Home\Controller;

use Think\Controller;


class ChangeController extends HomeController {



	public function index(){

		$this->display();




	}



	public function change(){
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}

		$user = session("user_auth");
		$user = M("customer")->find($user['id']);
		$this->assign("user", $user);
		$this->meta_title = '兑换关爱积分';
		$this->display();
	}
























}