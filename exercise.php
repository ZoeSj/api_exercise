<?php
/**
 * Created by PhpStorm.
 * User: shengjia
 * Date: 2018/8/28
 * Time: 17:15
 */
//error_reporting(E_ALL);
//function increment(&$var){
//    $var++;
//}
//
//$a = 0;
//call_user_func('increment',$a);
//echo $a."\n";
//
//call_user_func_array('increment',array(&$a));
//echo $a."\n";

namespace Foobar;
class Foo{
    static public function test(){
        print "hello world!\n";
    }
}

call_user_func(__NAMESPACE__.'\Foo::test');
call_user_func(array(__NAMESPACE__.'\Foo','test'));