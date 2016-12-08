<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class home{
    public function __construct() {
    
    }
    function index(){
		echo 'this is '.$_GET['username'].',s home! <BR/>';
    }

}
?>