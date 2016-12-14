<?php
defined('IS_RUN') or exit('/**error:404**/');
echo 'loading : '.__FILE__.'<br />';
class dog{
    public function __construct() {

    }
    function name(){
        ob_end_clean();
        echo "WangWang";
    }
    function mod(){
        $dog = M('dog');
        $dog->lists();

    }
    function talk(){
        $dog = C('dog');
        $dog->talk();
    }
    function eat(){
        $dog = C('fish');
        $dog->talk();
    }
    function bird(){
        $bird = C('bird',null,1,'animals');
        $bird->talk();
    }
   
}
?>