<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class Test{
    public function __construct() {
        
    }
    function test(){
        echo "new test()->test()<br />";
    }
    function ck(){
        echo CK('t').'<br />';
        CK('t',time());
    }
    function error(){
        ret_json(0,'error',1);
    }
}
?>