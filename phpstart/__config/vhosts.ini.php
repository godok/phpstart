<?php
defined('IS_RUN') or exit('/**error:404**/');
/**
*绑定程序
*host：域名，可以是正则表达式，第一个被匹配中的domain所对应的path为app目录
*path：程序目录
*get：前置get参数
*/
return array(
//程序目录绑定
	array(
		'host'=>'127.0.0.1',
		'path'=>'test',
		/*
		'get'=>array(
			'username'=>'admin'
		)
		*/
	),
	array(
		'host'=>'yy.mobi',
		'path'=>'app'
	)
);
?>