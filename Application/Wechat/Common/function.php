<?php
// 判断是否是在微信浏览器里
function isWeixinBrowser($from = 0) {
	if ((!$from && defined('IN_WEIXIN') && IN_WEIXIN) || isset ($_GET ['is_stree']))
		return true;

	$agent = $_SERVER ['HTTP_USER_AGENT'];
	if (!strpos($agent, "icroMessenger")) {
		return false;
	}
	return true;
}

// php获取当前访问的完整url地址
function get_current_url() {
	$url = 'http://';
	if (isset ($_SERVER ['HTTPS']) && $_SERVER ['HTTPS'] == 'on') {
		$url = 'https://';
	}
	if ($_SERVER ['SERVER_PORT'] != '80') {
		$url .= $_SERVER ['HTTP_HOST'] . ':' . $_SERVER ['SERVER_PORT'] . $_SERVER ['REQUEST_URI'];
	} else {
		$url .= $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
	}
	// 兼容后面的参数组装
	if (stripos($url, '?') === false) {
		$url .= '?t=' . time();
	}
	return $url;
}

// 获取当前用户的OpenId
function get_openid($openid = null) {

	$token = C("WX_TOKEN");
	if ($openid !== null && $openid != '-1') {
		session('openid_' . $token, $openid);
	} elseif (!empty ($_REQUEST ['openid']) && $_REQUEST ['openid'] != '-1' && $_REQUEST ['openid'] != '-2') {
		session('openid_' . $token, $_REQUEST ['openid']);
	}
	$openid          = session('openid_' . $token);
	$isWeixinBrowser = isWeixinBrowser();
	if ($_SERVER['HTTP_HOST'] == 'ht738.cn') {
		$isWeixinBrowser = true;
	}
	if ((empty ($openid) || $openid == '-1') && $isWeixinBrowser && $_REQUEST ['openid'] != '-2' && IS_GET && !IS_AJAX) {
		$callback = get_current_url();
		OAuthWeixin($callback, $token);
	}
	if (empty ($openid)) {
//		return '-1';
		exit ('please use wechat');
	}
	return $openid;
}

function OAuthWeixin($callback, $token = '') {
	if ((defined('IN_WEIXIN') && IN_WEIXIN) || isset ($_GET ['is_stree']))
		return false;

	$callback = urldecode($callback);

	if (strpos($callback, '?') === false) {
		$callback .= '?';
	} else {
		$callback .= '&';
	}

	$param ['appid'] = C('WX_APPID');

	if (empty ($param ['appid'])) {
		exit("no appid");
	}

	if (!isset ($_GET ['getOpenId'])) {
		$param ['redirect_uri']  = $callback . 'getOpenId=1';
		$param ['response_type'] = 'code';
		$param ['scope']         = 'snsapi_base';
		$param ['state']         = 123;
		$url                     = 'https://open.weixin.qq.com/connect/oauth2/authorize?' . http_build_query($param) . '#wechat_redirect';
		redirect($url);
	} elseif ($_GET ['state']) {
		$param ['code']       = I('code');
		$param ['grant_type'] = 'authorization_code';
		$param ['secret']     = trim(C('WX_APPSCRETE'));
		$url                  = 'https://api.weixin.qq.com/sns/oauth2/access_token?' . http_build_query($param);
		$content              = file_get_contents($url);
		$content              = json_decode($content, true);
		redirect($callback . 'openid=' . $content ['openid']);
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

function getTicketNo1($str){
	$temp=explode("|", $str);
	return $temp[0];
}

function getTicketNo2($str){
	$temp=explode("|", $str);
	return $temp[1];
}

function gettime($time){
	// return $time;
	$temp=explode("T", $time);
	$tmp = $temp[1];
	$tmp = explode(":", $tmp);

	return $tmp[0].":".$tmp[1];
}
