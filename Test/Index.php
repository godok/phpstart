<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class Index{
    public function __construct() {
    
    }
    function php(){
        phpinfo();
    }
    function index(){
		ob_end_clean();
		$time = time();
		$dh = (int)date("H",$time);//时
		$di = (int)date("i",$time);//分
		$ds = (int)date("s",$time);//秒
		$yci = "-".$ds;//分钟延迟
		$ych =  "-".$di*60+$ds;//小时延迟
		$words = PS::appConfig('words');
        include V();
    }
    function ck(){
        echo CK('t').'<br />';
        CK('t',time());
    }
    function error(){
        ret_json(0,'this is error msg',5);
    }
    function json(){
        ob_end_clean();
        $books = array(
            'total'=>'100',
            'category'=>'book',
            'list'=>array(
                array(
                    'name'=>'《phpstart》',
                    'page'=>50
                ),
                array(
                    'name'=>'《godok》',
                    'page'=>33
                )
            )
        );
        ret_json($books);
    }
    function xml(){
        ob_end_clean();
        $books = array(
            'total'=>'100',
            'category'=>'book',
            'list'=>array('book'=>array(
                array(
                    'name'=>'《phpstart》','page'=>66
                ),
                array(
                    'name'=>'《godok》',
                    'page'=>33
                )
            ))
        );


        ret_xml(array('cat','dog'));
    }
    function xml2array(){
        ob_end_clean();
        $books = '<xml><errNum>0</errNum><retMsg><![CDATA[success]]></retMsg><retData><total>100</total><category><![CDATA[book]]></category><list><book><name><![CDATA[《phpstart》]]></name><page>66</page></book><book><name><![CDATA[《godok》]]></name><page>33</page></book></list></retData></xml>';
        print_r(xml_decode($books));
    }
    function message(){
        ob_end_clean();
        message("SQL: <br/>select * from user<br/>Params:",0,array('首页'=>'xxxx','个人中心'=>'yyyy'),50000);
    }
}
?>