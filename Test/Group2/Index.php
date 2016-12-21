<?php
namespace Test\Group2;
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
        $cat = C('Cat');
        $cat->talk();
    }
    function dog(){
        echo "new index()->dog()<br />";
        $dog = C('Dog');
        $dog->talk();
    }
    function dog2(){
        echo "new index()->dog2()<br />";
        $dog = C('Dog','Group2');
        $dog->talk();
    }
    function dog3(){
        echo "new index()->dog3()<br />";
        $dog = C('Dog','/Test2/Group2');
        $dog->talk();
    }
    function book(){
        echo "new index()->book()<br />";
        $book = M('Book');
        $book->lists();
        $book = M('Book','/Test');
    }
    function student(){
        echo "new index()->student()<br />";
        $student = M('Student');
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
        include V('HelloWorld');
        echo '<br />';
        include V('HelloWorld','Group2');
    }
    function getCache(){
        print_r( PS::getCache('menu') );
    }
    function putCache(){
        echo 'filesize:'.PS::putCache('menu',array('home','book'));
    }
}
?>