<?php
namespace __Model;
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';

class Dog{
    public function __construct() {
        echo "__model\\dog...<br />";
    }
    function lists(){
        $book = M('book');
       $book->lists();
    }
}
?>