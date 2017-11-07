<?php
namespace core\lib;

/*
	自动加载
*/
class Register
{

	/*
		自动加载
		@param string $class 需要自动加载的类名

	*/
	public static function autoRegister($class){
		//目录
		$dir=PROJECT.str_replace('/',DIRECTORY_SEPARATOR,trim($class)).'.php';
		//引入文件
		include $dir;
	}

}