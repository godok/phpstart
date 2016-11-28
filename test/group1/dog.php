<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class dog{
    public function __construct() {
    
    }
    function name(){
        ob_end_clean();
        echo "WangWang";
    }
   
}
?>