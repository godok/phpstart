<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class Cat{
    public function __construct() {
    
    }
    function talk(){
        echo "i am cat<br />";
    }
}
?>