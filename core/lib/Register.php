<?php
spl_autoload_register(function($class){
		//根目录
		$basedir=__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
		//目录
		$dir=$basedir.str_replace('/',DIRECTORY_SEPARATOR,trim($class)).'.php';
		//引入文件
		include $dir;
	}	
);