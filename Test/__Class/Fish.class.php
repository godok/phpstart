<?php
namespace __Class;
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class Fish{
    public function __construct() {
        echo "__Class\\Fish...<br />";
    }
    function talk(){
       echo 'eating fish';
    }
}
?>