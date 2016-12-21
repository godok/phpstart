<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class Hello2{
    function index(){
        echo "new Hello()->index()<br />";
    }
    function world($p1='',$p2=''){
        echo "new Hello()->world()<br />";
        echo '$p1='.$p1.',$p2='.$p2.'<br />';
    }

}
?>