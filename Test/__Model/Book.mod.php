<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class Book{
    public function __construct() {
    
    }
    function lists(){
        echo "《PHPSTART教程》...<br />";
    }
}
?>