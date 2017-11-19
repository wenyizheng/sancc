<?php
namespace core\lib\view;

/*
	视图渲染类

*/

class ViewRender
{
	//模板文件内容
	protected $contents='';

	//系统内置标签
	protected $labels=[
		
	];

	/*
		渲染视图文件
		@param string $path 路径
		@return string 渲染后的html内容
	*/
	public function render($path)
	{

		//检测模板文件是否存在
		if(!file_exist($path.'.html')){
			throw new \Exception('模板文件不存在');
		}

		$contens=file_get_contents($path.'.html');

		if($contens===false){
			throw new \Exception('模板文件读取错误');
		}



	}
}