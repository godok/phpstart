<?php
defined('IS_RUN') or exit('/**error:404**/');
/**
*绑定程序
*domain：域名，可以是正则表达式，第一个被匹配中的domain所对应的path为app目录
*/
return array(
//程序目录绑定
	array(
		'domain'=>'127.0.01',
		'path'  =>'test'
	)
);
?>