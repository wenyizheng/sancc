<?php

/*
系统配置自动加载
*/

//include 'base.php';

$a=1;
set_exception_handler(function(Exception $e){
	echo $e->getMessage();
	echo "捕获到异常";

});
if($a==1){
	throw new Exception("error1");
}
restore_exception_handler();
if($a==1){
	throw new Exception("error2");
}



