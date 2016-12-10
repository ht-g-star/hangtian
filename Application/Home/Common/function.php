<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 前台公共库文件
 * 主要定义前台公共函数库
 */

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 *
 */
function check_verify($code, $id = 1) {
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}

/**
 * 获取列表总行数
 * @param  string $category 分类ID
 * @param  integer $status 数据状态
 *
 */
function get_list_count($category, $status = 1) {
	static $count;
	if (!isset($count[ $category ])) {
		$count[ $category ] = D('Document')->listCount($category, $status);
	}
	return $count[ $category ];
}

function get_group_price($unionid) {
	$unionid = explode('、', $unionid);
	$total   = "";
	foreach ($unionid as $val) {
		$id = $val;
		$total += get_good_price($id);
	}
	return $total;
}

function get_group_count($unionid) {
	$array  = explode('、', $unionid);
	$number = count($array);
	return $number;
}

function get_face($uid) {
	$comment   = M("ucenter_member");
	$map['id'] = $uid;
	$count     = $comment->where($map)->find();
	return $count["face"];
}

function get_comment_count($id) {
	$comment       = M("comment");
	$map['goodid'] = $id;
	$count         = $comment->where($map)->count();
	return $count;
}

function get_message_count($id) {
	$message       = M("message");
	$map['goodid'] = $id;
	$count         = $message->where($map)->count();
	return $count;
}

function get_group_marketprice($unionid) {
	$unionid = explode('、', $unionid);
	$total   = "";
	foreach ($unionid as $val) {
		$id = $val;
		$total += get_good_yprice($id);
	}
	if (!isset($total)) {
		$price = get_group_price($unionid);

	}
	return $total ? $total : $price;
}

function get_marketprice($id) {
	$groupbuying = M("document_groupbuying");
	$map['id']   = $id;
	$info        = $groupbuying->where($map)->find();
	return $info['market_price'] ? $info['market_price'] : 0;
}

function get_discount_Byid($id) {
	$groupbuying = M("document_limitbuying");
	$map['id']   = $id;
	$info        = $groupbuying->where($map)->find();
	$price       = get_good_price($id);
	$discount    = ($price * 10) / $info['market_price'];
	return sprintf("%1\$.1f", $discount);
}

/**
 * 获取段落总数
 * @param  string $id 文档ID
 * @return integer    段落总数
 *
 */
function get_part_count($id) {
	static $count;
	if (!isset($count[ $id ])) {
		$count[ $id ] = D('Document')->partCount($id);
	}
	return $count[ $id ];
}

function get_tuan_count($id) {
	$map['status']  = 1;
	$map['display'] = 1;
	$number         = M('document')->where("category_id='$id'")->count();
	return $number;
}

function get_shop_mobile($id) {
	$info = M('shop')->where("id='$id'")->find();
	return $info["mobile"];
}

function get_shop_address($id) {
	$info = M('shop')->where("id='$id'")->find();
	return $info["shopaddress"];
}


/**
 * 获取首页幻灯片
 * @param  string $url 导航URL
 * @return string      解析或的url
 *
 */
function get_slide() {
	$slide     = M('slide');
	$slidelist = $slide->where('status=1')->select();
	return $slidelist;
}

//在线交易订单支付处理函数
//函数功能：根据支付接口传回的数据判断该订单是否已经支付成功；
//返回值：如果订单已经成功支付，返回true，否则返回false；
function checkorderstatus($ordid) {
	$Ord       = M('Orderlist');
	$ordstatus = $Ord->where('ordid=' . $ordid)->getField('ordstatus');
	if ($ordstatus == 1) {
		return true;
	} else {
		return false;
	}
}


function setAirport($airportcode = ""){
    if(!empty($airportcode)){
        $airportArr = S("airportArr");

        if(empty($airportArr)){
            $airportList = M("jipiao_airport")->select();
            $airportArr = array();
            foreach ($airportList as $key => $value) {
                $airportArr[$value['airportcode']] = $value['airportname'];
            }
            S("airportArr", $airportArr, 86400);
        }

        $airportArr = S("airportArr");

        return $airportArr[$airportcode];
    }else{
        return "";
    }
}