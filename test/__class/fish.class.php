<?php
namespace __class;
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class fish{
    public function __construct() {
        echo "__class\\fish...<br />";
    }
    function talk(){
       echo 'eating fish';
    }
}
?>