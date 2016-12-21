<?php
namespace animals;
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class Bird{
    public function __construct() {
        echo "animals\\bird...<br />";
    }
    function talk(){
       echo 'i am a bird';
    }
}
?>