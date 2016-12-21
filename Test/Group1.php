<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class Group1{
    public function __construct() {
    
    }
    function index(){
		echo 'group1()->index()';
    }
    
  
}
?>