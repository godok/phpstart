<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class index{
    public function __construct() {
    
    }
    function index(){
        echo "new index()->index()<br />";
    }
}
?>