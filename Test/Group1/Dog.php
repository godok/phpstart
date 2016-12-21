<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class Dog{
    public function __construct() {
       
    }
    function name(){
        ob_end_clean();
        echo "WangWang";
    }
    function mod(){
        $dog = M('Dog');
        $dog->lists();

    }
    function talk(){
        $dog = C('Dog');
        $dog->talk();
    }
    function eat(){
        $dog = C('Fish');
        $dog->talk();
    }
    function bird(){
        $bird = C('Bird',null,1,'animals');
        $bird->talk();
    }
   
}
?>