<?php
namespace core\lib;

/*
路由文件
*/

class Route
{
	/*
	路由执行

	@return [string] 错误信息
	*/
	public function route()
	{
		//获取访问请求并拆分
		$access=explode('/',substr($_SERVER['REQUEST_URI'],19));
		if(empty($access['2'])||$this->routeCheck($access)!==true){
			return '路由访问错误';
			die();
		}

		
		
	}
	/*
	检测路由各部分是否正确

	@param Array $route 路由访问数组
	@return boole 成功 -true 失败 -false
	*/
	public function routeCheck(Array $route)
	{
		//检测模块是否存在
		is_dir($route['0'])?'':function(){return false;};
		//检测控制器是否存在
		is_dir($route['1'])?'':function(){return false;};
		//检测操作是否存在
		is_dir($route['2'])?'':function(){return false;};

		return true;
	}
}