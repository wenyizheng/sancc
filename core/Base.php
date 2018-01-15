<?php
include 'lib'.DIRECTORY_SEPARATOR.'Register.php';



//自动加载器
spl_autoload_register(function($class){

    //判断操作系统
    if(PHP_OS=='Linux'){
        $class=str_replace('\\','/',$class);
    }
		\core\lib\Register::autoRegister($class);
	}
);

//屏蔽所有错误信息显示
//ini_set('display_errors','off');
//异常处理程序
set_exception_handler(function($e){
	new \core\lib\Exceptions($e);
});
//错误处理程序
set_error_handler(function($errno,$errstr,$errfile,$errline){
	new \core\lib\Errors($errno,$errstr,$errfile,$errline);
},$errortype=E_STRICT);
//set_error_handler无法自动捕获的错误级别
define('E_FATAL',  E_ERROR | E_USER_ERROR |  E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR| E_PARSE );
register_shutdown_function(function(){
	$error=error_get_last();
	if($error && ($error["type"]===($error["type"] & E_FATAL))){
		new \core\lib\Errors($error['type'],$error['message'],$error['file'],$error['line']);
	}
});

$routeobj=\core\lib\route\Route::getInstance();
//加载用户配置文件
$routefile=include PROJECT.'config'.DS.'route.php';
$registerobj=\core\lib\route\RouteRegister::getInstance();
$registerobj->register($routefile);

//调用路由
$route=new \core\lib\Run();
$route->execute();

