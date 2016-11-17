<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class index{
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
    function book(){
        echo "new index()->book()<br />";
        $book = M('book');
        $book->lists();
    }
    function student(){
        echo "new index()->student()<br />";
        $student = M('student');
        $student->lists();
    }
    function myfun(){
        echo "new index()->myfunc()<br />";
        F('myfunc');
        myname();
    }
    function cfg(){
        echo "new index()->cfg()<br />";
        $student = S('student.lists');
        echo $student;
    }
    function template(){
        include V('helloworld');
        echo '<br />';
        include V('helloworld','group2');
    }
}
?>