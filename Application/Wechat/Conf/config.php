<?php
return array (
	//'配置项'=>'配置值'
	'URL_MODEL'          => 1,
	'SESSION_AUTO_START' => true, //是否开启session
	/* SESSION 和 COOKIE 配置 */
	'SESSION_PREFIX'     => 'onethink_home', //session前缀
	'COOKIE_PREFIX'      => 'onethink_home_', // Cookie前缀 避免冲突
	/* 模板相关配置 */
	'TMPL_PARSE_STRING'  => array (
		'__STATIC__' => __ROOT__ . '/Public/static',
		'__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Addons',
		'__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
		'__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
		'__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
	),
);