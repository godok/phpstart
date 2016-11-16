<?php
/**
 *  core.php PHPSTART框架入口文件
 *
 * @copyright			(C) 2016-2020 PHPSTART
 * @license				http://phpstart.xyz/license/
 * @lastmodify			2016-10-1
 * SCRIPT_PATH : php脚本的路径
 * SCRIPT_NAME : php脚本文件
 */
define('IS_RUN', true);//
if(!isset($_SESSION)) session_start();
//系统开始时间
define('SYS_START_TIME', microtime());
define('SYS_TIME', time());
//define('PHPSTART_ROOT', dirname(__FILE__));
define('PHPSTART_ROOT', dirname(__FILE__));//phpstart内核目录
defined('DOCUMENT_ROOT') or define('DOCUMENT_ROOT', trim(str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']),'/'));//站点根目录


//GPC安全过滤,如果数据库用PDO预处理则不需要再addslashes
if(!get_magic_quotes_gpc()){
    //ps::addslashes($_POST);
    //ps::addslashes($_GET);
    //ps::addslashes($_COOKIE);
}

//当前访问的主机名
define('HTTP_HOST', (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));
//来源
define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
/*
 * 获取最终路由
 */
//配置APP路径
$app_path = trim(str_replace('\\','/',dirname($_SERVER['SCRIPT_FILENAME'])),'/');//app目录
defined('APP_PATH') or define('APP_PATH', ps::app_path());



$path_info = isset($_SERVER['PATH_INFO']) ? trim(strtolower(str_replace('\\','/',$_SERVER['PATH_INFO'])),'/') : '';
if(!empty($path_info) && strpos($path_info,'.') > 0){

    $temp_ = explode('.',$path_info);
    $path_info = $temp_[0];
}


if(!empty(APP_PATH)) {
    $temp_ =  trim(str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'])),'/');
    if(!empty($temp_)){
        $path_info = empty($path_info)  ? $temp_ : $temp_.'/'.$path_info;
    }
    $app_path = DOCUMENT_ROOT.'/'.APP_PATH;
}elseif($app_path == DOCUMENT_ROOT && !empty($path_info)) {
     $temp_ = explode('/',$path_info);
     $app_path.='/'.array_shift($temp_);
     $path_info = empty($temp_) ? 'index':implode('/',$temp_);
}
if(empty($path_info)) $path_info = 'index';

defined('APP_ROOT') or define('APP_ROOT',$app_path);//app目录


//缓存文件夹地址
defined('CACHE_PATH') or define('CACHE_PATH', APP_ROOT.'/cache');

$path_array = empty($path_info) ? null : explode('/',$path_info);

$file = empty($path_array) ? 'index':array_pop($path_array);

define('SCRIPT_PATH',empty($path_array) ? '' : implode('/',$path_array));
define('SCRIPT_NAME',$file);
//echo APP_ROOT.'<br />'.SCRIPT_PATH.'<br />'.SCRIPT_NAME.'<br />';
//防止变量污染
unset($url_path);
unset($path_array);
unset($temp_);
unset($file);
unset($app_path);
//加载系统库
ps::sys_func('global');
ps::sys_func('pdo');
ps::sys_class('phpstart');
//SESSION安全
isset($_SESSION['ip']) && ps::sys_config('system.check_ip') && $_SESSION['ip'] != $_SERVER["REMOTE_ADDR"] && session_unset();
$_SESSION['ip'] = $_SERVER["REMOTE_ADDR"];
//输出页面字符集
define('CHARSET',ps::sys_config('system.charset'));
header('Content-type: text/html; charset='.CHARSET);
if(ps::sys_config('system.gzip') && function_exists('ob_gzhandler')) {
    ob_start('ob_gzhandler');
} else {
    ob_start();
}
//设置本地时差
function_exists('date_default_timezone_set')&& ps::sys_config('system.timezone') && date_default_timezone_set(ps::sys_config('system.timezone'));
ps::sys_config('system.debug') ? error_reporting(E_ALL) : error_reporting(0);
ps::sys_config('system.errorlog') && set_error_handler('my_error_handler');

//初始化app
if (!file_exists(APP_ROOT)){
    echo '/**error**/';exit;
}
if (APP_ROOT != DOCUMENT_ROOT && !file_exists(APP_ROOT.'/cache/')){
    ps::init();
}
//启动程序
ps::runapp();


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
	    $path_array = empty(SCRIPT_PATH) ? array('') : explode('/','/'.SCRIPT_PATH);
	    $script_path = APP_ROOT.'/';
	    while(!empty($path_array)){
	       $temp_ = array_shift($path_array);
	       if(!empty($temp_)) $script_path .= $temp_.'/';
	       if (file_exists($script_path.'_init.php')) {
	           require_once $script_path.'_init.php';
	       }
	    };
	    unset($path_array);
	    if($script_path.$script_name == $_SERVER['SCRIPT_FILENAME']) {echo '/**error**/';exit;}
	    if (file_exists($script_path.$script_name)) {
	        
	        require_once $script_path.$script_name;
	    }else{
	        echo '/**not found php script**/';exit;
	    }
	    
	}
	/**
	 * 加载配置文件
	 * @param string $file 配置文件
	 * @param string $key  要获取的配置荐
	 * @param string $default  默认配置。当获取配置项目失败时该值发生作用。
	 * @param boolean $reload 强制重新加载。
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
	 * @param string $file 配置文件
	 * @param string $key  要获取的配置荐
	 * @param string $default  默认配置。当获取配置项目失败时该值发生作用。
	 * @param boolean $reload 强制重新加载。
	 */
	public static function get_config( $key = '',$path = '', $default = '', $reload = false) {

	    static $configs = array();
	    $key_array = explode('.',$key);
	    $file = array_shift($key_array);
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
	        $path_array = empty($path) ? array('') : explode('/','/'.$path);
	    }
	    //从目录开始往上遍历
	    while(!empty($path_array)){
	        $temp_ = implode('/',$path_array);
	        $config_file = empty($temp_) ? $root.'/config/'.$file.'.ini.php' : $root.'/'.implode('/',$path_array).'/config/'.$file.'.ini.php';
	    
	        if (file_exists($config_file)) {
	            $configs[$key] = include $config_file;
	            if (empty($key_array)) {
	                
	                return $configs[$key];
	            } else {
	                return get_array_value($configs[$key],$key_array,$default);
	            }
	            break;
	        }else{
	            array_pop($path_array);
	        }
	    };
	    return $default;
	}
	/**
	 * 加载系统类方法
	 * @param string $classname 类名
	 * @param intger $initialize 是否初始化
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
	 * @param string $classname 类名
	 * @param intger $initialize 是否初始化
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
	 * @param string $func 函数库名
	 * @param string $path 地址
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
	 * @param string $classname 类名
	 * @param string $path 路径
	 * @param intger $initialize 是否初始化
	 */
	public static function app_class($classname, $path='',$initialize = 1) {
	    static $classes = array();
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
	        $path_array = empty($path) ? array('') : explode('/','/'.$path);
	    }
	    
	    //从脚本所在目录开始往上遍历
	    while(!empty($path_array)){
	        $temp_ = implode('/',$path_array);
	        $script_name = empty($temp_) ? $root.'/class/'.$classname.'.class.php': $root.'/'.implode('/',$path_array).'/class/'.$classname.'.class.php';
	        if (file_exists($script_name)) {
	  
	            require_once $script_name;
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
	        }
	        
	        array_pop($path_array);
	    };
	    
	    
	    //未找到classname
	    $classes[$key] = false;
	    return $classes[$key];
	   
	}
	/**
	 * 加载app模型
	 * @param string $modelname 类名
	 * @param string $path 路径
	 * @param intger $initialize 是否初始化
	 */
	public static function app_model($modelname, $path='',$initialize = 1) {
	    static $models = array();
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
	        $path_array = empty($path) ? array('') : explode('/','/'.$path);
	    }
	    //从脚本所在目录开始往上遍历
	    while(!empty($path_array)){
	        $temp_ = implode('/',$path_array);
	        $script_name = empty($temp_) ? $root.'/model/'.$modelname.'.mod.php': $root.'/'.implode('/',$path_array).'/model/'.$modelname.'.mod.php';
	        if (file_exists($script_name)) {
	             
	            require_once $script_name;
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
	        }
	         
	        array_pop($path_array);
	    };
	    //未找到modelname
	    $models[$key] = false;
	    return $models[$key];
	
	}
	/**
	 * 加载app函数库
	 * @param string $libs 函数库名
	 * @param string $path 路径
	 */
	public static function app_func($functionname, $path='') {
	    static $funcs = array();
	    if (empty($path)) $path = SCRIPT_PATH;
	    $key = md5($path.$functionname);
	    if (isset($funcs[$key])) return true;
	     
	    if(substr($path,0,1) == '/'){
	        $root = DOCUMENT_ROOT;
	        $path_array =  explode('/',$path);
	         
	    }else{
	        $root = APP_ROOT;
	        $path_array = empty($path) ? array('') : explode('/','/'.$path);
	    }
	    //从脚本所在目录开始往上遍历
	    while(!empty($path_array)){
	        $temp_ = implode('/',$path_array);
	        $script_name = empty($temp_) ? $root.'/function/'.$functionname.'.func.php': $root.'/'.implode('/',$path_array).'/function/'.$functionname.'.func.php';
	        if (file_exists($script_name )) {
	            require_once $script_name ;
	            $funcs[$key] = true;
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
	 * @param string $libname 插件名
	 * @param string $path 路径
	 */
	public static function app_lib($libname, $path='') {
	    static $funcs = array();
	    if (empty($path)) $path = SCRIPT_PATH;
	    $key = md5($path.$libname);
	    if (isset($libs[$key])) return true;
	
	    if(substr($path,0,1) == '/'){
	        $root = DOCUMENT_ROOT;
	        $path_array =  explode('/',$path);
	         
	    }else{
	        $root = APP_ROOT;
	        $path_array = empty($path) ? array('') : explode('/','/'.$path);
	    }
	    //从脚本所在目录开始往上遍历
	    while(!empty($path_array)){
	        $temp_ = implode('/',$path_array);
	        $script_name = empty($temp_) ? $root.'/lib/'.$libname.'.php': $root.'/'.implode('/',$path_array).'/lib/'.$libname.'.php';
	        if (file_exists($script_name)) {
	            require_once $script_name;
	            $libs[$key] = true;
	            return $libs[$key];
	        }
	
	        array_pop($path_array);
	    };
	    //未找到functionname
	    $libs[$key] = false;
	    return $libs[$key];
	
	}
	/**
	 * app文件夹
	 */
	public static function app_path(){
	    static $configs = array();
	    if (!empty($configs)) {
	        return  isset($configs[HTTP_HOST]) ? $configs[HTTP_HOST] : '';
	    }
	    $config_file = PHPSTART_ROOT.'/config/apppath.ini.php';
        if (file_exists($config_file)) {
            $configs = include $config_file;
            return  isset($configs[HTTP_HOST]) ? $configs[HTTP_HOST] : '';
        }else{
            return  '';
        }

	}
	/**
	 * 防止xss注入
	 */
	public static function addslashes(&$arr) {
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
	}
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
	}
	
}