<?php
namespace core\lib;

/*
	文件操作
*/

class Files
{
	/*
		打开文件
		@param string $path 文件路径
		@param string $type 文件打开方式

	*/
	public function open($path,$type='w+')
	{
		//检测文件是否存在
		if(!file_exists($path)){
			throw new \Exception('文件不存在');
		}

		$file=fopen($path,$type);

		if($file===false){
			throw new \Exception('文件打开失败');
		}

		return $file;
	}

	
}