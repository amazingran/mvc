<?php
class BaseClass{
    const CITY='shenz';
    static $name='hello';
    static function say(){
        echo self::$name;
    }

    function go(){
        echo 'ok';
    }
}
?>
<?php
class SonClass extends BaseClass{
    function show(){
        echo parent::$name;
    }
}

?>

<?php

SonClass::show();
$son=new SonClass();
$son->go();


define(SEX,'man');






















/*function foo($n, $f='') {
    if($n < 1) return;
    for($i=0; $i<$n; $i++) {
        echo $f ? $f($i) : $i;
    }
}
//无回调时
foo(5); //01234

//有回调时
function f1($v) {
    return $v + $v;
}
foo(5, 'f1'); //02468*/


