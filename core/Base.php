<?php

//自动加载器
spl_autoload_register(function($class){
		//根目录
		$basedir=__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
		//目录
		$dir=$basedir.str_replace('/',DIRECTORY_SEPARATOR,trim($class)).'.php';
		//引入文件
		include $dir;
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
//调用路由
\core\lib\Route::route();
