<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class index{
    public function __construct() {
    
    }
    function index(){
		ob_end_clean();
		$time = time();
		$dh = (int)date("H",$time);//时
		$di = (int)date("i",$time);//分
		$ds = (int)date("s",$time);//秒
		$yci = "-".$ds;//分钟延迟
		$ych =  "-".$di*60+$ds;//小时延迟
		$words = ps::app_config('words');
        include V();
    }
    function ck(){
        echo CK('t').'<br />';
        CK('t',time());
    }
    function error(){
        json_message('error',0);
    }
}
?>