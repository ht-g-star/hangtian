<?php
/**
 * +----------------------------------------------------------------------
 * |统一支付平台 —— 入口
 * +----------------------------------------------------------------------
 * |Copyright (c) 2016 http://www.wx738.com All rights reserved.
 * +----------------------------------------------------------------------
 * |Author: g-star <gucy@wx738.com> <http://www.wx738.com>
 * +----------------------------------------------------------------------
 */


namespace Pay\Controller;

class IndexController extends PayController {	
	
	/**
	 * 支付入口画面  
	 * 先期只做电脑端的支付画面  后期可能需要维护修改
	 */
	public function index(){
		
    }
    
    
    /**
     * 余额支付
     * @param $orderid  Array() 为一组若干个订单号
     * 
     * 
     * 
     */
    public function balance() {  	
    	$orderid  = I('post.orderid');    	
    	if(!is_array($orderid)){
    		$this->error(array('rst'=>FALSE,'msg'=>'参数错误。'));
    		return;
    	}
    	
    	//过滤 去除空的订单号
    	$idArr    = array_map('safe_replace',array_filter($orderid));  	
    	if(empty($idArr)){
    		$this->error(array('rst'=>FALSE,'msg'=>'无有效的订单。'));
    		return;
    	}
    	
    	$password   = I("post.password", "0", 'md5');

    	$login_user = M("customer")->where("id=%d", $this->uid)->find();
    	if ($login_user['password'] != $password) {
    		$this->error(array('rst'=>FALSE,'msg'=>'密码错误!'));
    	}
    	   	
    	/*
    	 * 查询并判断每一个订单号是否有效
    	 */
    	$order = new \Pay\Logic\OrderLogic($this->uid, $login_user);
    	$orderList = $order->requestManyOrder($idArr);

    	//订单提交结果
    	if($orderList['rst']){    		
    		parent::success("支付成功!", U('Home/Center/tobeshipped'));
    	}else{
    		$this->error($orderList['prompt']);    		
    		return;
    	}
    }
    

    
    
    
}