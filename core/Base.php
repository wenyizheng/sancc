<?php
include 'lib'.DIRECTORY_SEPARATOR.'Register.php';
//快速函数
include 'lib'.DIRECTORY_SEPARATOR.'func'.DIRECTORY_SEPARATOR.'FastFunc.php';

use \core\lib\config\BuildConfig;

//自动加载器
spl_autoload_register(function($class){
		\core\lib\Register::autoRegister($class);
	}	
);

//异常处理程序
set_exception_handler(function($e){
	new \core\lib\Exceptions($e);
});
//错误处理程序
set_error_handler(function($errno,$errstr,$errfile,$errline){
	new \core\lib\Errors($errno,$errstr,$errfile,$errline);
});

//加载用户配置文件

//调用路由
//\core\lib\Route::route();
$route=new \core\lib\route\Run();
$route->execute();
//$route->abc();
var_dump(Param('name'));