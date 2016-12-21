<?php
//namespace __Model;
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
//class Dog{
class Dog_mod{
    public function __construct() {
        echo "__model\\Dog...<br />";
    }
    function lists(){
        $book = M('Book');
       $book->lists();
    }
}
?>