<?php
/**
 * +----------------------------------------------------------------------
 * |统一支付平台
 * +----------------------------------------------------------------------
 * |Copyright (c) 2016 http://www.wx738.com All rights reserved.
 * +----------------------------------------------------------------------
 * |Author: g-star <gucy@wx738.com> <http://www.wx738.com>
 * +----------------------------------------------------------------------
 */

namespace Pay\Controller;
use Think\Controller;

/**
 * @author 		g-star
 * $uid  		登录用户的id
 * $payFrom 	支付提交的目录来自于PC还是微信
 * $login_user	登录用的的session信息
 * 
 */
abstract class PayController extends Controller {
	protected $uid,$payFrom;
	
	
	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}
	
	
	protected function _initialize(){
        /* 读取站点配置 */
	    $config = S('DB_CONFIG_DATA');
	    if (!$config) {
		    $config = api('Config/lists');
		    S('DB_CONFIG_DATA', $config);
	    }
	    C($config); //添加配置		
        if(!C('WEB_SITE_CLOSE')){
            $this->error('站点已经关闭，请稍后访问~');
        }
        //获取用户session中user的Id
        $this->uid = is_login();
        $login_user = session('user_auth');
        
        if (!$this->uid) {        	     
        	if(isset($login_user['id'])){
        		$this->error("您还没有登陆", U("Home/User/login"));
        	}else{
        		//微信跳转
        		
        	}
        }else{
        	$this->payFrom = isset($login_user['uid']) ? 'Wechat' : 'PC';
        }
    }
    
    public abstract function balance(); 
    
    
}