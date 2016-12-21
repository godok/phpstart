<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class index{
    public function __construct() {
    
    }
    function index(){
        echo "new index()->index()<br />";
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