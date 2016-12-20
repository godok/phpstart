<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class Index{
    public function __construct() {
        
    }
    function index(){
        echo "new index()->index()<br />";
    }
    function cat(){
        echo "new index()->cat()<br />";
        $cat = C('cat');
        $cat->talk();
    }
    function dog(){
        echo "new index()->dog()<br />";
        $dog = C('dog');
        $dog->talk();
    }
    function dog2(){
        echo "new index()->dog2()<br />";
        $dog = C('dog','group2');
        $dog->talk();
    }
    function dog3(){
        echo "new index()->dog3()<br />";
        $dog = C('dog','/test2/group2');
        $dog->talk();
    }
    function book(){
        echo "new index()->book()<br />";
        $book = M('book');
        $book->lists();
        $book = M('book','/test');
    }
    function student(){
        echo "new index()->student()<br />";
        $student = M('student');
        $student->lists();
    }
    function myfunc(){
        echo "new index()->myfunc()<br />";
        F('myfunc');
        myname();
    }
    function lib(){
        echo "new index()->lib()<br />";
        L('lib');
        L('phpExcel/phpExcel.php');
    }
    function cfg(){
        echo "new index()->cfg()<br />";
        $student = PS::appConfig('student.lists');
        echo $student;
    }
    function template(){
        include V('helloworld');
        echo '<br />';
        include V('helloworld','group2');
    }
    function getCache(){
        print_r( PS::getCache('menu') );
    }
    function putCache(){
        echo 'filesize:'.PS::putCache('menu',array('home','book'));
    }
}
?>