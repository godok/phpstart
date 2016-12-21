<?php
/**
 *  core.php PHPSTART框架入口文件
 *
 * @copyright			(C) 2016-2020 PHPSTART
 * @license				http://phpstart.xyz/license/
 * @lastmodify			2016-11-17
 */
//定义phpstart的系统常量
define('IS_RUN', true);
if(!isset($_SESSION)) session_start();
define('SYS_START_TIME', microtime());
define('SYS_TIME', time());
defined('PHPSTART_VERSION') or define('PHPSTART_VERSION', '1.0');//版本号
defined('DEFAULT_APP') or define('DEFAULT_APP', 'test');//默认APP目录
define('PHPSTART_ROOT', dirname(__FILE__));//phpstart内核目录
defined('DOCUMENT_ROOT') or define('DOCUMENT_ROOT', rtrim(str_replace('\\','/',$_SERVER['SCRIPT_FILENAME']),'/'));//phpstart项目根目录
define('HTTP_HOST', (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));
define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
   defined('IS_AJAX') or define('IS_AJAX', TRUE);
}else{
   defined('IS_AJAX') or define('IS_AJAX', FALSE);
}
$params = PS::urlRouter();
//加载系统库
PS::sysFunc('global');
PS::sysFunc('pdo');
//SESSION安全：如果用户的ip变更则判断为cookie盗窃者，可在系统配置中关闭
isset($_SESSION['ip']) && PS::appConfig('system.check_ip') && $_SESSION['ip'] != $_SERVER["REMOTE_ADDR"] && session_unset();
$_SESSION['ip'] = $_SERVER["REMOTE_ADDR"];
//从配置文件加载编码类型
define('CHARSET',PS::appConfig('system.charset'));
header('Content-type: text/html; charset='.CHARSET);
//GZIP处理
if(PS::appConfig('system.gzip') && function_exists('ob_gzhandler')) {
    ob_start('ob_gzhandler');
} else {
    ob_start();
}
//设置时区
function_exists('date_default_timezone_set')&& PS::appConfig('system.timezone') && date_default_timezone_set(PS::appConfig('system.timezone'));
PS::appConfig('system.debug') ? error_reporting(E_ALL) : error_reporting(0);
PS::appConfig('system.errorlog') && set_error_handler('my_error_handler');
//判断程序目录是否存在
if (!is_dir(APP_ROOT)){
    _404();exit;
}
//判断程序目录是否已初始化，如果未初始化则执行初始化
if (!is_file(APP_ROOT.'/__Config/database.ini.php')){
    PS::init();
}
//启动程序
PS::runApp($params);
/**
 * phpstart核心类
 */
final class PS {
	/**
	 * 初始化应用程序
	 */
	public static function runApp($params) {
	    static $runtimes;
	    //只能运行一次runApp
	    if($runtimes) return false;
	    $runtimes = true;
	    $classname = CONTROLLER;
	    if(SCRIPT_PATH !=''){
	        //带名字空间的控制器名
	        $classname2 = str_replace('/','\\',SCRIPT_PATH).'\\'.CONTROLLER;
	    }
	    $funname = ACTION;
		//加载php脚本
	    self::loadScript($classname);
	    if(class_exists($classname)){ 
	        $obj = new $classname;
	        if(method_exists($obj,$funname)){
	            if(empty($params)){
	                $obj->$funname();
	            }else{
	                call_user_func_array(array($obj,$funname),$params);
	            }
	        }
	    }elseif(isset($classname2) && class_exists($classname2)){
	        $obj = new $classname2;
	        if(method_exists($obj,$funname)){
	            if(empty($params)){
	                $obj->$funname();
	            }else{
	                call_user_func_array(array($obj,$funname),$params);
	            }
	        }
	    }
	}
	/**
	 * 从bin加载入口脚本文件
	 */
	public static function loadScript(){
	    if(SCRIPT_PATH == ''){
	        $path_array =  array();
	    }else{
	        $path_array =  explode('/',SCRIPT_PATH);
	    }
	    $script_path = APP_ROOT.'/';
	    //加载文件夹配置
	    do{
	       if (is_file($script_path.'__init.php')) require_once $script_path.'__init.php';
	       if(!empty($path_array)){
	           $temp_ = array_shift($path_array);
	           if(!empty($temp_)){
	               $script_path .= $temp_.'/';
	               empty($path_array) && is_file($script_path.'__init.php') && require_once $script_path.'__init.php';
	           }
	           
	       }
	    }while(!empty($path_array));
	    unset($path_array);
	    if(strtolower($script_path.CONTROLLER.'.php') == strtolower($_SERVER['SCRIPT_FILENAME'])) {echo '/**error**/';exit;}
	    //加载php脚本
	    if (is_file($script_path.CONTROLLER.'.php')) {     
	        require_once $script_path.CONTROLLER.'.php';
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
	public static function sysConfig( $key = '', $default = '', $reload = false) {
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
	    $config_file = PHPSTART_ROOT.'/__Config/'.$file.'.ini.php';
	    if (is_file($config_file)) {
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
	public static function appConfig( $key = '',$path = '', $default = '', $reload = false) {
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
	        $path_array =  explode('/','/'.$path);
	    }
	    empty($path_array[1]) && array_pop($path_array);
	    //从目录开始往上遍历
	    do{
	        $temp_ = trim(implode('/',$path_array),'/');
	        $config_file = empty($temp_) ? $root.'/__Config/'.$file.'.ini.php' : $root.'/'.$temp_.'/__Config/'.$file.'.ini.php';    
	        if (is_file($config_file)) {
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
	public static function sysClass($classname, $initialize = 1) {
	    static $classes = array();
	    $key = md5($classname);
	    if (isset($classes[$key])) {
	        if (!empty($classes[$key])) {
	            return $classes[$key];
	        } else {
	            return true;
	        }
	    }
	    if (is_file(PHPSTART_ROOT.'/__Class/'.$classname.'.class.php')) {
			require_once PHPSTART_ROOT.'/__Class/'.$classname.'.class.php';
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
    public static function sysModel($modelname, $initialize = 1) {
	    static $models = array();
	    $key = md5($modelname);
	    if (isset($models[$key])) {
	        if (!empty($models[$key])) {
	            return $models[$key];
	        } else {
	            return true;
	        }
	    }
	    if (is_file(PHPSTART_ROOT.'/__Model/'.$modelname.'.class.php')) {
	        require_once PHPSTART_ROOT.'/__Model/'.$modelname.'.class.php';
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
	public static function sysFunc($func) {
	    static $funcs = array();
	    $key = md5($func);
	    if (isset($funcs[$key])) return true;
	    if (is_file(PHPSTART_ROOT.'/__Function/'.$func.'.func.php')) {
	        require_once PHPSTART_ROOT.'/__Function/'.$func.'.func.php';
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
	public static function appClass($classname, $path='',$initialize = 1,$namespace='__Class') {
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
	        $path_array =  explode('/','/'.$path);
	    }
	    empty($path_array[1]) && array_pop($path_array);
	    //从脚本所在目录开始往上遍历
	    while(!empty($path_array)){
	        $temp_ = trim(implode('/',$path_array),'/');
	        $script_name = empty($temp_) ? $root.'/__Class/'.$classname.'.class.php': $root.'/'.$temp_.'/__Class/'.$classname.'.class.php';
	        
	        if (is_file($script_name)) {
	            $key2 = md5($script_name);
	            if (!isset($classes[$key2])) require_once $script_name;
	    
	            if ($initialize) {
	                $classname_class = $classname."_class";
	                $classname_name = "\\".$namespace."\\".$classname;
	                if(class_exists($classname_name)){
	                    $classes[$key] = $classes[$key2] = new $classname_name;
	                }elseif(class_exists($classname_class)){
	                    $classes[$key] = $classes[$key2] = new $classname_class;
	                }elseif(class_exists($classname)){
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
	public static function appModel($modelname, $path='',$initialize = 1,$namespace='__Model') {
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
	        $path_array =  explode('/','/'.$path);
	    }
	    empty($path_array[1]) && array_pop($path_array);
	    //从脚本所在目录开始往上遍历
	    while(!empty($path_array)){
	        $temp_ = trim(implode('/',$path_array),'/');
	        $script_name = empty($temp_) ? $root.'/__Model/'.$modelname.'.mod.php': $root.'/'.$temp_.'/__Model/'.$modelname.'.mod.php';
	        if (is_file($script_name)) {
	            $key2 = md5($script_name);
	            if (!isset($models[$key2])) require_once $script_name;
	            if ($initialize) {
	                $modelname_mod = $modelname."_model";
	                $modelname_name = "\\".$namespace."\\".$modelname;
	                if(class_exists($modelname_mod)){
	                    $models[$key] = $models[$key2] = new $modelname_mod;
	                }elseif(class_exists($modelname_name)){
	                    $models[$key] = $models[$key2] = new $modelname_name;
	                }elseif(class_exists($modelname)){
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
	public static function appFunc($functionname, $path='') {
	    static $funcs = array();
	  
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
	        $script_name = empty($temp_) ? $root.'/__Function/'.$functionname.'.func.php': $root.'/'.$temp_.'/__Function/'.$functionname.'.func.php';
	        if (is_file($script_name )) {
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
	public static function appLib($libname, $path='') {
	    static $libs = array();
	
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
	        $script_name = empty($temp_) ? $root.'/__Lib/'.$libname: $root.'/'.$temp_.'/__Lib/'.$libname;
	        
	        if (is_file($script_name)) {
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
	public static function getCache( $filename = '',$path = '') {
	    $path = trim($path,'/');
	    if (empty($path)) $path = APP_PATH;
	    if(substr($filename,-10) != '.cache.php') $filename.='.cache.php';
	    $file = DOCUMENT_ROOT.'/'.$path.'/__Cache/'.$filename;
	    if (is_file($file)) {
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
	public static function putCache( $filename = '',$data='',$path = '') {
	    $path = trim($path,'/');
	    if (empty($path)) $path = APP_PATH;
	    if(substr($filename,-10) != '.cache.php') $filename.='.cache.php';
	    $file = DOCUMENT_ROOT.'/'.$path.'/__Cache/'.$filename;
	    $data = "<?php\ndefined('IS_RUN') or exit('/**error:404**/');\nreturn ".var_export($data, true).";\n?>";
	    $size = @file_put_contents($file, $data, LOCK_EX);
	    return $size ? $size : 'false';
	}
	/**
	 * app文件夹
	 */
	public static function appName(){
	    static $appname = null;
	    if($appname !== null) return $appname;
	    $config_file = PHPSTART_ROOT.'/__Config/vhosts.ini.php';
        if (is_file($config_file)) {
            $configs = include $config_file;
            foreach($configs as $app){
                if($app['host'] == HTTP_HOST){
                    $appname = trim($app['path'],'/');
                    return $appname;
                }
                if(@preg_match('/'.trim($app['host'],'/').'/',HTTP_HOST)){
                    $appname = trim($app['path'],'/');
                    return $appname;
                }
            }
            $appname = trim(DEFAULT_APP,'/');
        }else{
            $appname = trim(DEFAULT_APP,'/');
        }
        return $appname;
	}
	/**
	 * app前置GET变量
	 */
	public static function appPreGet(){

	    $config_file = PHPSTART_ROOT.'/__Config/vhosts.ini.php';
	    if (is_file($config_file)) {
	        $configs = include $config_file;
	        foreach($configs as $app){
	            if($app['host'] == HTTP_HOST){
	                if(isset($app['get'])) return $app['get'];
	            }
	            if(@preg_match('/'.trim($app['host'],'/').'/',HTTP_HOST)){
	                if(isset($app['get'])) return $app['get'];
	            }
	        }
	        return  array();
	    }else{
	        return  array();
	    }
	}
	/**
	 * URL路由处理
	 */
	public static function urlRouter(){
	    //环境变量PATH_INFO作为路由变量
	    $path_info = isset($_SERVER['PATH_INFO']) ? str_replace('\\','/',$_SERVER['PATH_INFO']) : '';
	    //“.”和“/”都作为单词分隔符
	    $path_info = str_replace('.','/',$path_info);
	    //去掉首尾的单词分隔符，并把路由变量转成单词数组
	    $path_info = trim($path_info,'/');
	    if(empty($path_info)){
	        $path_array = array();
	    }else{
	        $path_array = explode('/',$path_info);
	    }
	    //处理URL前置$_GET变量
	    $pre_get = PS::appPreGet();
	    foreach($pre_get as $key=>$val){
	        if(!empty($path_array)){
	            $_GET[$key] = array_shift($path_array);
	        }else{
	            $_GET[$key] = $val;
	        }
	    }
	    //路由变量为空时，默认为Index
	    empty($path_array) && $path_array = array('Index');
	    $app_root = DOCUMENT_ROOT;
	    //判断路由变量第一个单词是否是app目录
	    if(PS::appName() == DEFAULT_APP && is_file($app_root.'/'.ucfirst($path_array[0]).'/__Config/database.ini.php') && !is_file($app_root.'/'.ucfirst($path_array[0]).'/PS.sign')){
	        //把第一个单词作为程序名（程序文件夹名）
	        defined('APP_PATH') or define('APP_PATH', ucfirst(array_shift($path_array)));
	        empty($path_array) && $path_array=array('Index');
	    }else{
	        defined('APP_PATH') or define('APP_PATH', PS::appName());//绑定的APP相对路径 
	    }
	    APP_PATH!='' && $app_root = $app_root.'/'.APP_PATH;
	    defined('APP_ROOT') or define('APP_ROOT',$app_root);//app目录
	    defined('CACHE_PATH') or define('CACHE_PATH', APP_ROOT.'/__Cache');//缓存目录
	    //参数
	    $params = array();
	    //复制一份不ucfirst的路由变量，再把路由变量的每个单词首字母大写
	    $path_array_real = $path_array;
	    foreach($path_array as &$val){
	        $val = ucfirst($val);
	    }
	    while(!empty($path_array)){
	        $temp_ = trim(implode('/',$path_array),'/');
	        $script_name = APP_ROOT.'/'.$temp_.'.php';
	        //判断路由变量是否是php脚本文件
	        if(is_file($script_name)){
	            define('SCRIPT_NAME',array_pop($path_array));//请求的php脚本名
	            array_pop($path_array_real);
	            break;
	        }
	        $script_name2 = APP_ROOT.'/'.$temp_.'/Index.php';
	        //判断路由变量对应的目录下是否有Index.php
	        if(empty($params) && !is_file($script_name) && is_file($script_name2)){
	            define('SCRIPT_NAME','Index');
	            break;
	        }
	        //往上一层遍历
	        array_unshift($params,array_pop($path_array_real));
	        array_pop($path_array);
	    };
	    
	    defined('SCRIPT_NAME') or define('SCRIPT_NAME', 'Index');//缓存目录
	    define('SCRIPT_PATH',empty($path_array) ? '' : implode('/',$path_array));//脚本相对路径
	    $classname = SCRIPT_NAME;
	    if(empty($params)){
	        $funname = 'index';
	    }else{
	        $funname = array_shift($params);
	    }
	    //定义控制器和方法
	    define('CONTROLLER',$classname);
	    define('ACTION',$funname);
	    //回收内存
	    unset($path_array);
	    unset($path_array_real);
	    unset($temp_);
	    unset($app_root);
	    return $params;
	}
	/**
	 * 初始化程序目录
	 */
	public static function init(){
	    if(!is_dir(APP_ROOT.'/__Cache/')) mkdir(APP_ROOT.'/__Cache');
	    if(!is_dir(APP_ROOT.'/__Cache/Tpl')) mkdir(APP_ROOT.'/__Cache/Tpl');
	    if(!is_dir(APP_ROOT.'/__Config/')) mkdir(APP_ROOT.'/__Config');
	    if(!is_dir(APP_ROOT.'/__Function/')) mkdir(APP_ROOT.'/__Function');
	    if(!is_dir(APP_ROOT.'/__Class/')) mkdir(APP_ROOT.'/__Class');
	    if(!is_dir(APP_ROOT.'/__Model/')) mkdir(APP_ROOT.'/__Model');
	    if(!is_dir(APP_ROOT.'/__Lib/')) mkdir(APP_ROOT.'/__Lib');
	    if(!is_dir(APP_ROOT.'/__Tpl/')) mkdir(APP_ROOT.'/__Tpl');
	    if(!is_file(APP_ROOT.'/__Config/database.ini.php')){
	       $str = @file_get_contents (PHPSTART_ROOT.'/__Config/database.ini.php');
	       file_put_contents (APP_ROOT.'/__Config/database.ini.php', $str );
	    }
	    if(!is_file(APP_ROOT.'/__Config/system.ini.php')){
	        $str = @file_get_contents (PHPSTART_ROOT.'/__Config/system.ini.php');
	        file_put_contents (APP_ROOT.'/__Config/system.ini.php', $str );
	    }
	}
	
}