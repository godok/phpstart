<?php
defined('IS_RUN') or exit('/**error:404**/');
/**
*绑定程序
*host：域名，可以是正则表达式，第一个被匹配中的domain所对应的path为app目录
*path：程序目录
*gets：前置get参数
*router：路由变量 ，默认$_SERVER['PATH_INFO']
*/
return array(
//程序目录绑定
	array(
		'host'=>'127.0.0.1',
		'path'=>'Test',
		/*
		'gets'=>array(
			'username'=>'admin'
		),
		'router'=>isset($_GET['r']) ? $_GET['r'] : '',
		*/
	),
	array(
		'host'=>'localhost',
		'path'=>'Test2'
	)
);
?>