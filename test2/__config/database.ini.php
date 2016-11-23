<?php
defined('IS_RUN') or exit('/**error:404**/');
/**
 * 
 */
return array(
	'master'=>array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'port' => '3306',
		'database' => 'phpstart',
		'charset' => 'utf8',
		'pconnect' => 0,
		'tablepre' => '',
		'maxtime'=>6
	),
    'test'=>array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'port' => '3306',
        'database' => 'test',
        'charset' => 'utf8',
        'pconnect' => 0,
        'tablepre' => '',
        'maxtime'=>6
    )
);


?>