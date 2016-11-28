<?php
defined('IS_RUN') or exit('/**error:404**/');
return array(
//网站路径
'web_path' => '/',
//Session配置
'check_ip' => 1,//核对session的IP


'charset' => 'utf-8', //网站字符集
'timezone' => 'Etc/GMT-8', //网站时区（只对php 5.1以上版本有效），Etc/GMT-8 实际表示的是 GMT+8

'debug' => 1, //是否显示调试信息
'errorlog' => 0, //1、保存错误日志到 cache/error_log.php | 0、在页面直接显示
'gzip' => 0, //是否Gzip压缩后输出
'auth_key' => 'www.phpstart.xyz', //密钥
'lang' => 'zh-cn',  //网站语言包

//Cookie配置
'cookie'=>array(
	'domain' => '', //Cookie 作用域,留空为当前域名
	'path' => '/', //Cookie 作用路径
	'pre' => 'ps_', //Cookie 前缀，
	'expire' => 3600, //Cookie 生命周期，0 表示随浏览器进程
	'httponly' => false,//httponly
),
//后缀
'suffixes'=>array('php','html')
);
?>