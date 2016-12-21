<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class Phpstart{
    public function __construct() {
    
    }
    function version(){
		ob_end_clean();
		echo PHPSTART_VERSION;
    }
    function author(){
        ob_end_clean();
        echo 'Fafa';
    }
}
?>