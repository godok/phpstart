<?php
defined('IS_RUN') or exit('/**error:404**/');
/**
*  global.func.php 系统全局函数库
*
* @copyright			(C) 2016-2020 PHPSTART
* @license				http://phpstart.xyz/license/
* @lastmodify			2016-10-1
*/
/**
* 脚本文件路径script_path()
* 相对于document_root的路径，方便站内遍历
* 参数1:_FILE_ 
*/
function script_path($file){
   return trim(str_replace(DOCUMENT_ROOT,'',str_replace('\\','/',dirname($file))),'/');
}
/**
* 输出自定义错误
*
* @param $errno 错误号
* @param $errstr 错误描述
* @param $errfile 报错文件地址
* @param $errline 错误行号
* @return string 错误提示
*/
function my_error_handler($errno, $errstr, $errfile, $errline) {
   if($errno==8) return '';
   $errfile = str_replace(DOCUMENT_ROOT,'',str_replace('\\','/',$errfile));
   if(ps::app_config('system.errorlog')) {
	   error_log('<?php exit;?>'.date('m-d H:i:s',SYS_TIME).' | '.$errno.' | '.str_pad($errstr,30).' | '.$errfile.' | '.$errline."\r\n", 3, CACHE_PATH.'error_log.php');
   } else {
	   $str = "<span>errorno:' . $errno . ',str:' . $errstr . ',file:<font color='blue'>' . $errfile . '</font>,line' . $errline .'<br /></span>";
	   show_error($str);
   }
}
/**
* 通过键名数组从数组中获取指定值
* @param 数组
* @param 键名
* @param 默认值
*/
function get_array_value($data,$key_array,$default){
   do{
	   $key = array_shift($key_array);
	   if (isset($data[$key])) {
		   $data = $data[$key];
	   }else{
		   return $default;
	   }
   
	   
   }while(!empty($key_array));
   return $data;
}
/**
* 写cookie
*/
function set_cookie($key, $value, $expire = '',$path='',$domain='',$httponly = '') {

   $config = ps::app_config('system.cookie');
   $expire = $expire != '' ? $expire :  $config['expire'];
   $path = $path != '' ? $path :  $config['path'];
   $domain = $domain != '' ? $domain :  $config['domain'];
   $httponly = $httponly != '' ? $httponly :  $config['httponly'];
   $expire = $expire != 0 ? (SYS_TIME + $expire) : 0;
   $secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
   return setcookie($config['pre'] . $key, $value, $expire, $config['path'], $config['domain'], $secure, $httponly);
}
/**
* 读cookie
*/
function get_cookie($key) {
   $config = ps::app_config('system.cookie');
  
   return isset($_COOKIE[$config['pre'].$key])  ? $_COOKIE[$config['pre'].$key] : false;
}

/**
* 智能读写COOKIES
*/
function CK($key, $value=NULL, $expire = '',$path='',$domain='',$httponly = '') {
   if($value==NULL && $expire == '' && $path=='' && $domain=='' && $httponly == ''){
	   return get_cookie($key);
   }else{
	   set_cookie($key, $value, $expire ,$path,$domain,$httponly);
   }
   
}
/**
* 模板调用
*
* @param $template
* @param $path
*/
function template($template='', $path = '') {
   if(empty($template)) $template = ACTION;
   if (empty($path)) $path = SCRIPT_PATH;
	
   if(substr($template,0,1) == '/'){
	   $tpl_file = APP_ROOT.'/tpl'.$template.'.html';
	   
   }else{
	   $path_array = empty($path) ? array('') : explode('/','/'.$path);
	   //从脚本所在目录开始往上遍历
	   while(!empty($path_array)){
		   $temp_ = implode('/',$path_array);
		   $tpl_file =empty($temp_) ? APP_ROOT.'/tpl/'.$template.'.html' : APP_ROOT.'/'.implode('/',$path_array).'/tpl/'.$template.'.html';
		   if (file_exists($tpl_file)) break;
		   array_pop($path_array);
	   };
   }
   
   if(!file_exists($tpl_file)) $tpl_file = PHPSTART_ROOT.'/tpl/'.trim($template,'/').'.html';
   if(!file_exists($tpl_file)) show_error('templatefile:'.str_replace(DOCUMENT_ROOT,'',$tpl_file)." is not exists!");
   $cache_file = CACHE_PATH.'/tpl/'.md5($tpl_file).'.cache.php';
   if(!file_exists($cache_file) ||  filemtime($tpl_file) > filemtime($cache_file)) {
	   $template_cache = ps::sys_class('template');
	   $template_cache->refresh($tpl_file, $cache_file);
   }
   return $cache_file;
}
/**
* 提示信息页面跳转，跳转地址如果传入数组，页面会提示多个地址供用户选择，默认跳转地址为数组的第一个值，时间为3秒。
* message('error', 'error',array('到首页'=>'/'));
* @param string $msg 提示信息
* @param string $style 消息类型
* @param mixed(string/array) $url_forward 跳转地址
* @param int $ms 跳转等待时间，0为不跳转
*/
function message($msg='error',$style='error', $url_forward = '', $ms = 3000) {
    include(template('/message'));
	exit;
}
/**
* json_message('登录成功', 1,'/');
* @param string $msg 提示信息
* @param int $code 消息代码
* @param mixed(string/array) $url_forward 跳转地址
*/
function json_message($msg='error',$code=0, $url_forward = '') {
    $result = array(
        'code'=>$code,
        'data' =>$msg
    );
    empty($url_forward) || $result['forward'] = $url_forward;
    $code==0 && $result['error'] = $msg;
    echo json_encode($result);
    exit;
}
/**
* 系统错误消息
*/
function show_error($msg = 'error'){
    $str = '<html><head><title>error_message</title><style>body {	background-color: #fff;	margin: 30px;	font: 13px/20px ;	color: #333;}a {	color: #039;	background-color: transparent;	font-weight: normal;}h1 {	color: #444;	background-color: transparent;	border-bottom: 1px solid #D0D0D0;	font-size: 19px;	font-weight: normal;	margin: 0 0 14px 0;	padding: 14px 15px;}code {	font-size: 12px;	background-color: #f9f9f9;	border: 1px solid #D0D0D0;	color: #002166;	margin: 14px 0 ;	padding: 12px ;}#container {	margin: 10px;	border: 1px solid #D0D0D0;	-webkit-box-shadow: 0 0 8px #D0D0D0;}p {	margin: 12px 15px 12px 15px;}</style></head><body>	<div id="container">		<h1>Message!</h1>		<p>'.$msg.'</p></div></body></html>';
    if(ps::app_config("system.debug")) echo $str;
    exit;
    
}
/**
* 加载app模型
* @param string $modelname 类名
* @param string $path 路径
* @param intger $initialize 是否初始化
*/
function M($modelname, $path='',$initialize = 1) {
    return ps::app_model($modelname, $path,$initialize);
}
/**
* 模板调用
*
* @param $template
* @param $istag
* @return unknown_type
*/
function V($template = '', $path = '') {
  return template($template, $path );
}
/**
* 加载app类方法
* @param string $classname 类名
* @param string $path 路径
* @param intger $initialize 是否初始化
*/
function C($classname, $path='',$initialize = 1) {
     return ps::app_class($classname, $path,$initialize);
}
/**
* 加载app函数库
* @param string $functionname 函数库名
* @param string $path 路径
*/
function F($functionname, $path='') {
	return ps::app_func($functionname, $path);
}
/**
 * 加载app类库
 * @param string $functionname 函数库名
 * @param string $path 路径
 */
function L($functionname, $path='') {
    return ps::app_lib($functionname, $path);
}
/**
* 加载配置文件
* @param string $file 配置文件
* @param string $key  要获取的配置荐
* @param string $default  默认配置。当获取配置项目失败时该值发生作用。
* @param boolean $reload 强制重新加载。
*/
function S($key = '',$path = '', $default = '', $reload = false) {
    return ps::app_config( $key ,$path, $default, $reload );
}
/**
* 跳转页面
*/
function _302($url){
    header("Location: $url");
    exit;
}
/**
* 防止xss注入
*/
function X(&$arr) {
    if(get_magic_quotes_gpc()) return true;
    if (is_array($arr)){
        foreach ($arr as $key => &$value) {
            if (!is_array($value)){
                if (!is_numeric($value)){
                    $value=@addslashes($value);
                }
            }else{
                antixss($arr[$key]);
            }
        }
    }
    return true;
}
?>