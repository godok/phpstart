<?php
/**
 *  core.php PHPSTART框架入口文件
 *
 * @copyright			(C) 2016-2020 PHPSTART
 * @license				http://phpstart.xyz/license/
 * @lastmodify			2016-11-17
 */
define('IS_RUN', true);//
if(!isset($_SESSION)) session_start();
define('SYS_START_TIME', microtime());
define('SYS_TIME', time());
defined('DEFAULT_APP') or define('DEFAULT_APP', 'test');//默认APP目录
define('PHPSTART_ROOT', dirname(__FILE__));//phpstart内核目录
defined('DOCUMENT_ROOT') or define('DOCUMENT_ROOT', trim(str_replace('\\','/',$_SERVER['SCRIPT_FILENAME']),'/'));//phpstart项目根目录
define('HTTP_HOST', (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));
define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');

$path_info = isset($_SERVER['PATH_INFO']) ? strtolower(str_replace('\\','/',$_SERVER['PATH_INFO'])) : '';
substr($path_info,-1) == '/' && $path_info .= 'index';
$path_info = trim($path_info,'/');
empty($path_info) && $path_info = 'index';

if(strpos($path_info,'.') > 0){
    $temp_ = explode('.',$path_info);
    $path_info = $temp_[0];
}
$path_array = explode('/',$path_info);
$app_root = DOCUMENT_ROOT;
/**
 * 一级目录是app目录
 */
if(file_exists($app_root.'/'.$path_array[0].'/config/database.ini.php')){
    defined('APP_PATH') or define('APP_PATH', array_shift($path_array));//绑定的APP相对路径
    $app_root = $app_root.'/'.APP_PATH;
    
    empty($path_array) && $path_array=array('index');
}else{
    defined('APP_PATH') or define('APP_PATH', ps::app_path());//绑定的APP相对路径
    empty(APP_PATH) || $app_root = $app_root.'/'.APP_PATH;
}

defined('APP_ROOT') or define('APP_ROOT',$app_root);//app目录
defined('CACHE_PATH') or define('CACHE_PATH', APP_ROOT.'/cache');//缓存目录

$file = array_pop($path_array);
define('SCRIPT_PATH',empty($path_array) ? '' : implode('/',$path_array));//URI路由
define('SCRIPT_NAME',$file);//请求的php脚本
unset($url_path);
unset($path_array);
unset($temp_);
unset($file);
unset($app_root);
/**
 * 加载系统库
 */
ps::sys_func('global');
ps::sys_func('pdo');
ps::sys_class('phpstart');
/**
 * SESSION安全，
 */
isset($_SESSION['ip']) && ps::app_config('system.check_ip') && $_SESSION['ip'] != $_SERVER["REMOTE_ADDR"] && session_unset();
$_SESSION['ip'] = $_SERVER["REMOTE_ADDR"];
/**
 * 输出配置
 */
define('CHARSET',ps::app_config('system.charset'));
header('Content-type: text/html; charset='.CHARSET);
if(ps::app_config('system.gzip') && function_exists('ob_gzhandler')) {
    ob_start('ob_gzhandler');
} else {
    ob_start();
}
function_exists('date_default_timezone_set')&& ps::app_config('system.timezone') && date_default_timezone_set(ps::app_config('system.timezone'));
ps::app_config('system.debug') ? error_reporting(E_ALL) : error_reporting(0);
ps::app_config('system.errorlog') && set_error_handler('my_error_handler');
/**
 * 初始化程序目录
 */
if (!file_exists(APP_ROOT)){
    _404();exit;
}
if (!file_exists(APP_ROOT.'/config/database.ini.php')){
    ps::init();
}
ps::runapp();//启动程序
/**
 * phpstart核心类
 */
class ps {
	/**
	 * 初始化应用程序
	 */
	public static function runapp() {
	    static $runtimes;
	    if($runtimes) return false;//只能运行一次runapp
	    $runtimes = true;
		$file_array = explode('-',SCRIPT_NAME);
		if(count($file_array) == 1 ) $file_array[1] = 'index';
		$file = $file_array[0].'.php';
		$classname = array_shift($file_array);
		$funname = array_shift($file_array);
		define('CONTROLLER',$classname);
		define('ACTION',$funname);
	    self::bin_file($file);
	    if(class_exists($classname)){ 
	        $obj = new $classname;
	        if(method_exists($obj,$funname)){
	            if(empty($file_array)){
	                $obj->$funname();
	            }else{
	                call_user_func_array(array($obj,$funname),$file_array);
	            }
	        }
	    }
	}
	/**
	 * 从bin加载入口脚本文件
	 */
	public static function bin_file($script_name){
	    $path_array =  explode('/',SCRIPT_PATH);
	    $script_path = APP_ROOT.'/';
	    do{
	       if (file_exists($script_path.'_init.php')) require_once $script_path.'_init.php';
	       $temp_ = array_shift($path_array);
	       empty($temp_) || $script_path .= $temp_.'/';
	       empty($path_array) && file_exists($script_path.'_init.php') && require_once $script_path.'_init.php';
	    }while(!empty($path_array));
	    unset($path_array);
	    if($script_path.$script_name == $_SERVER['SCRIPT_FILENAME']) {echo '/**error**/';exit;}
	    if (file_exists($script_path.$script_name)) {     
	        require_once $script_path.$script_name;
	    }else{
	        _404();
	    }
	}
	/**
	 * 加载配置文件
	 * @param string 配置文件.参数
	 * @param string 默认配置。当获取配置项目失败时该值发生作用。
	 * @param boolean 强制重新加载。
	 */
	public static function sys_config( $key = '', $default = '', $reload = false) {
	    static $configs = array();
	    $key_array = explode('.',$key);
	    $file = array_shift($key_array);
	    $key = md5($file);
	    if (!$reload && isset($configs[$key])) {
	        if (empty($key_array)) {
	            return $configs[$key];
	        } else {
	            return get_array_value($configs[$key],$key_array,$default);
	        }
	    }
	    $config_file = PHPSTART_ROOT.'/config/'.$file.'.ini.php';
	    if (file_exists($config_file)) {
	        $configs[$key] = include $config_file;
	        if (empty($key_array)) {
	            return $configs[$key];
	        } else {
	            return get_array_value($configs[$key],$key_array,$default);
	        }
	    }else{
	        return $default;
	    }
	}
	/**
	 * 加载配置文件
	 * @param string 配置文件.参数
	 * @param string 路径
	 * @param string 默认配置。当获取配置项目失败时该值发生作用。
	 * @param boolean 强制重新加载。
	 */
	public static function app_config( $key = '',$path = '', $default = '', $reload = false) {
	    static $configs = array();
	    $key_array = explode('.',$key);
	    $file = array_shift($key_array);
	    $path = trim($path,'/');
	    if (empty($path)) $path = SCRIPT_PATH;
	    $key = md5($path.$file);
	    if (!$reload && isset($configs[$key])) {
	        if (empty($key_array)) {
	            return $configs[$key];
	        } else {
	            return get_array_value($configs[$key],$key_array,$default);
	        }
	    }
	    if(substr($path,0,1) == '/'){
	        $root = DOCUMENT_ROOT;
	        $path_array =  explode('/',$path);
	    }else{
	        $root = APP_ROOT;
	        $path_array =  explode('/','/'.$path);
	    }
	    empty($path_array[1]) && array_pop($path_array);
	    //从目录开始往上遍历
	    do{
	        $temp_ = trim(implode('/',$path_array),'/');
	        $config_file = empty($temp_) ? $root.'/config/'.$file.'.ini.php' : $root.'/'.$temp_.'/config/'.$file.'.ini.php';    
	        if (file_exists($config_file)) {
	            $key2 = md5($config_file);
	            if (!isset($configs[$key2])) $configs[$key] = $configs[$key2] = include $config_file;
	            if (empty($key_array)) {
	                
	                return $configs[$key2];
	            } else {
	                return get_array_value($configs[$key2],$key_array,$default);
	            }
	            break;
	        }else{
	            array_pop($path_array);
	        }
	    }while(!empty($path_array));
	    return $default;
	}
	/**
	 * 加载系统类方法
	 * @param string 类名
	 * @param intger 是否初始化
	 */
	public static function sys_class($classname, $initialize = 1) {
	    static $classes = array();
	    $key = md5($classname);
	    if (isset($classes[$key])) {
	        if (!empty($classes[$key])) {
	            return $classes[$key];
	        } else {
	            return true;
	        }
	    }
	    if (file_exists(PHPSTART_ROOT.'/class/'.$classname.'.class.php')) {
			require_once PHPSTART_ROOT.'/class/'.$classname.'.class.php';
			if ($initialize) {
			    if(class_exists($classname)){
			        $classes[$key] = new $classname;
			    }else{
			        $classes[$key] = false;
			    }
				
			} else {
				$classes[$key] = true;
			}
			return $classes[$key];
		} else {
			return false;
		}
	}
	/**
	 * 加载系统模型
	 * @param string 类名
	 * @param intger 是否初始化
	 */
    public static function sys_model($modelname, $initialize = 1) {
	    static $models = array();
	    $key = md5($modelname);
	    if (isset($models[$key])) {
	        if (!empty($models[$key])) {
	            return $models[$key];
	        } else {
	            return true;
	        }
	    }
	    if (file_exists(PHPSTART_ROOT.'/model/'.$modelname.'.class.php')) {
	        require_once PHPSTART_ROOT.'/model/'.$modelname.'.class.php';
	        if ($initialize) {
	            if(class_exists($modelname)){
	                $models[$key] = new $modelname;
	            }else{
	                $models[$key] = false;
	            }
	
	        } else {
	            $models[$key] = true;
	        }
	        return $models[$key];
	    } else {
	        return false;
	    }
	}
	/**
	 * 加载系统函数库
	 * @param string 函数库名
	 */
	public static function sys_func($func) {
	    static $funcs = array();
	    $key = md5($func);
	    if (isset($funcs[$key])) return true;
	    if (file_exists(PHPSTART_ROOT.'/function/'.$func.'.func.php')) {
	        require_once PHPSTART_ROOT.'/function/'.$func.'.func.php';
	    } else {
	        $funcs[$key] = false;
	        return false;
	    }
	    $funcs[$key] = true;
	    return true;
	}
	/**
	 * 加载app类方法
	 * @param string 类名
	 * @param string 路径
	 * @param intger 是否初始化
	 */
	public static function app_class($classname, $path='',$initialize = 1) {
	    static $classes = array();
	    $path = trim($path,'/');
	    if (empty($path)) $path = SCRIPT_PATH;
	    $key = md5($path.$classname);
	    
	    if (isset($classes[$key])) {
	        if (!empty($classes[$key])) {
	            return $classes[$key];
	        } else {
	            return true;
	        }
	    }
	    if(substr($path,0,1) == '/'){
	        $root = DOCUMENT_ROOT;
	        $path_array =  explode('/',$path);
	    }else{
	        $root = APP_ROOT;
	        $path_array =  explode('/','/'.$path);
	    }
	    empty($path_array[1]) && array_pop($path_array);
	    //从脚本所在目录开始往上遍历
	    while(!empty($path_array)){
	        $temp_ = trim(implode('/',$path_array),'/');
	        $script_name = empty($temp_) ? $root.'/class/'.$classname.'.class.php': $root.'/'.$temp_.'/class/'.$classname.'.class.php';
	        if (file_exists($script_name)) {
	            $key2 = md5($script_name);
	            if (!isset($classes[$key2])) require_once $script_name;
	    
	            if ($initialize) {
    	            if(class_exists($classname)){
    			        $classes[$key] = $classes[$key2] = new $classname;
    			    }else{
    			        $classes[$key] = $classes[$key2] = false;
    			    }
	            } else {
	                $classes[$key] = $classes[$key2] = true;
	            }
	            return $classes[$key];
	        }
	        
	        array_pop($path_array);
	    };
	    //未找到classname
	    $classes[$key] = false;
	    return $classes[$key];
	   
	}
	/**
	 * 加载app模型
	 * @param string 模型名
	 * @param string 路径
	 * @param intger 是否初始化
	 */
	public static function app_model($modelname, $path='',$initialize = 1) {
	    static $models = array();
	    $path = trim($path,'/');
	    if (empty($path)) $path = SCRIPT_PATH;
	    $key = md5($path.$modelname);
	    if (isset($models[$key])) {
	        if (!empty($models[$key])) {
	            return $models[$key];
	        } else {
	            return true;
	        }
	    }
	    if(substr($path,0,1) == '/'){
	        $root = DOCUMENT_ROOT;
	        $path_array =  explode('/',$path);
	    }else{
	        $root = APP_ROOT;
	        $path_array =  explode('/','/'.$path);
	    }
	    empty($path_array[1]) && array_pop($path_array);
	    //从脚本所在目录开始往上遍历
	    while(!empty($path_array)){
	        $temp_ = trim(implode('/',$path_array),'/');
	        $script_name = empty($temp_) ? $root.'/model/'.$modelname.'.mod.php': $root.'/'.$temp_.'/model/'.$modelname.'.mod.php';
	        if (file_exists($script_name)) {
	            $key2 = md5($script_name);
	            if (!isset($models[$key2])) require_once $script_name;
	            if ($initialize) {
	                if(class_exists($modelname)){
	                    $models[$key] = $models[$key2] = new $modelname;
	                }else{
	                    $models[$key] = $models[$key2] = false;
	                }
	            } else {
	                $models[$key] = $models[$key2] = true;
	            }
	            return $models[$key];
	        }
	         
	        array_pop($path_array);
	    };
	    //未找到modelname
	    $models[$key] = false;
	    return $models[$key];
	}
	/**
	 * 加载app函数库
	 * @param string 函数库名
	 * @param string 路径
	 */
	public static function app_func($functionname, $path='') {
	    static $funcs = array();
	    $path = trim($path,'/');
	    if (empty($path)) $path = SCRIPT_PATH;
	    $key = md5($path.$functionname);
	    if (isset($funcs[$key])) return true;
	     
	    if(substr($path,0,1) == '/'){
	        $root = DOCUMENT_ROOT;
	        $path_array =  explode('/',$path);
	    }else{
	        $root = APP_ROOT;
	        $path_array =  explode('/','/'.$path);
	    }
	    empty($path_array[1]) && array_pop($path_array);
	    //从脚本所在目录开始往上遍历
	    while(!empty($path_array)){
	        $temp_ = trim(implode('/',$path_array),'/');
	        $script_name = empty($temp_) ? $root.'/function/'.$functionname.'.func.php': $root.'/'.$temp_.'/function/'.$functionname.'.func.php';
	        if (file_exists($script_name )) {
	            $key2 = md5($script_name);
	            if (!isset($funcs[$key2])) require_once $script_name;
	            $funcs[$key] = $funcs[$key2] = true;
	            return $funcs[$key];
	        }
	        array_pop($path_array);
	    };
	    //未找到functionname
	    $funcs[$key] = false;
	    return $funcs[$key];
	}
	/**
	 * 加载app插件
	 * @param string 插件名
	 * @param string 路径
	 */
	public static function app_lib($libname, $path='') {
	    static $libs = array();
	    $path = trim($path,'/');
	    if (empty($path)) $path = SCRIPT_PATH;
	    if(substr($libname,-4) != '.php') $libname.='.php';
	    
	    $key = md5($path.$libname);
	    if (isset($libs[$key])) return true;
	    if(substr($path,0,1) == '/'){
	        $root = DOCUMENT_ROOT;
	        $path_array =  explode('/',$path);
	    }else{
	        $root = APP_ROOT;
	        $path_array =  explode('/','/'.$path);
	    }
	    empty($path_array[1]) && array_pop($path_array);
	    //从脚本所在目录开始往上遍历
	    while(!empty($path_array)){
	        $temp_ = trim(implode('/',$path_array),'/');
	        $script_name = empty($temp_) ? $root.'/lib/'.$libname: $root.'/'.$temp_.'/lib/'.$libname;
	        
	        if (file_exists($script_name)) {
	            $key2 = md5($script_name);
	            if (!isset($libs[$key2])) require_once $script_name;
	            $libs[$key] = $libs[$key2] = true;
	            return $libs[$key];
	        }
	        array_pop($path_array);
	    };
	    //未找到functionname
	    $libs[$key] = false;
	    return $libs[$key];
	}
	/**
	 * 获取缓存
	 * @param string 配置文件名， 不带.cache.php
	 * @param string 程序名，默认当前程序名 ，对应系统常量 APP_PATH
	 */
	public static function get_cache( $filename = '',$path = '') {
	    $path = trim($path,'/');
	    if (empty($path)) $path = APP_PATH;
	    if(substr($filename,-10) != '.cache.php') $filename.='.cache.php';
	    $file = DOCUMENT_ROOT.'/'.$path.'/cache/'.$filename;
	    if (file_exists($file)) {
	       $cache = include $file;
	       return $cache;
	    }
	    return false;
	}
	/**
	 * 写缓存
	 * @param string 配置文件名， 不带.cache.php
	 * @param string or array，缓存内容
	 * @param string 程序名，默认当前程序名 ，对应系统常量 APP_PATH
	 */
	public static function put_cache( $filename = '',$data='',$path = '') {
	    $path = trim($path,'/');
	    if (empty($path)) $path = APP_PATH;
	    if(substr($filename,-10) != '.cache.php') $filename.='.cache.php';
	    $file = DOCUMENT_ROOT.'/'.$path.'/cache/'.$filename;
	    $data = "<?php\ndefined('IS_RUN') or exit('/**error:404**/');\nreturn ".var_export($data, true).";\n?>";
	    $size = @file_put_contents($file, $data, LOCK_EX);
	    return $size ? $size : 'false';
	}
	/**
	 * app文件夹
	 */
	public static function app_path(){
	    static $configs = array();
	    if (!empty($configs)) {
	        return  isset($configs[HTTP_HOST]) ? $configs[HTTP_HOST] : DEFAULT_APP;
	    }
	    $config_file = PHPSTART_ROOT.'/config/vhosts.ini.php';
        if (file_exists($config_file)) {
            $configs = include $config_file;
            foreach($configs as $app){
                if($app['host'] == HTTP_HOST) return trim($app['path'],'/');
                if(@preg_match('/'.trim($app['host'],'/').'/',HTTP_HOST)) return trim($app['path'],'/');
            }
            return  trim(DEFAULT_APP,'/');
        }else{
            return  trim(DEFAULT_APP,'/');
        }
	}
	/**
	 * 初始化程序目录
	 */
	public static function init(){
	    if(!file_exists(APP_ROOT.'/cache/')) mkdir(APP_ROOT.'/cache');
	    if(!file_exists(APP_ROOT.'/cache/tpl')) mkdir(APP_ROOT.'/cache/tpl');
	    if(!file_exists(APP_ROOT.'/config/')) mkdir(APP_ROOT.'/config');
	    if(!file_exists(APP_ROOT.'/function/')) mkdir(APP_ROOT.'/function');
	    if(!file_exists(APP_ROOT.'/class/')) mkdir(APP_ROOT.'/class');
	    if(!file_exists(APP_ROOT.'/model/')) mkdir(APP_ROOT.'/model');
	    if(!file_exists(APP_ROOT.'/lib/')) mkdir(APP_ROOT.'/lib');
	    if(!file_exists(APP_ROOT.'/tpl/')) mkdir(APP_ROOT.'/tpl');
	    if(!file_exists(APP_ROOT.'/config/database.ini.php')){
	       $str = @file_get_contents (PHPSTART_ROOT.'/config/database.ini.php');
	       file_put_contents (APP_ROOT.'/config/database.ini.php', $str );
	    }
	    if(!file_exists(APP_ROOT.'/config/system.ini.php')){
	        $str = @file_get_contents (PHPSTART_ROOT.'/config/system.ini.php');
	        file_put_contents (APP_ROOT.'/config/system.ini.php', $str );
	    }
	}
	
}