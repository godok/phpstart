<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class Dog_class{
    public function __construct() {
        echo "dog_class...<br />";
    }
    function talk(){
        echo "i am dog<br />";
    }
}
?>