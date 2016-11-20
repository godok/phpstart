<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class hello{
    function index(){
        echo "new hello()->index()<br />";
    }
    function world($p1='',$p2=''){
        echo "new hello()->world()<br />";
        echo '$p1='.$p1.',$p2='.$p2.'<br />';
    }

}
?>