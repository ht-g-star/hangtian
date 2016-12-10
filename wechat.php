<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
//
//$uri = $_SERVER['REQUEST_URI'];
//if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//	$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
//	$pos = array_search('unknown', $arr);
//	if (false !== $pos) unset($arr[ $pos ]);
//	$ip = trim($arr[0]);
//} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
//	$ip = $_SERVER['HTTP_CLIENT_IP'];
//} elseif (isset($_SERVER['REMOTE_ADDR'])) {
//	$ip = $_SERVER['REMOTE_ADDR'];
//}
//if (!empty($ip) && $ip == "222.191.249.90") {
//	header("Location: http://192.168.0.171".$uri);
//}else if($_SERVER['HTTP_HOST']=='ht738.vboshi.cn'){
//	header("Location: http://www.wx738.com".$uri);
//}




/**
 * 系统调试设置
 * 项目正式部署后请设置为false
 */
define('APP_DEBUG', true );
define('BIND_MODULE','Wechat');

/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
 */
define ( 'APP_PATH', './Application/' );


/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define ( 'RUNTIME_PATH', './Runtime/' );

/**
 * 引入核心入口
 * ThinkPHP亦可移动到WEB以外的目录
 */
require './ThinkPHP/ThinkPHP.php';