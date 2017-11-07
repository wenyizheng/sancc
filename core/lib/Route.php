<?php
namespace core\lib;
use \core\lib\BuildConfig;

/*
路由文件
*/

class Route
{


	/*
	路由执行
	@return [string] 错误信息
	*/
	public static function getRouteInfo()
	{
		$object=new static();
		
		//获取访问请求并拆分
		$access=explode('/',substr($_SERVER['REQUEST_URI'],19));

		return $access;
		/*if(empty($access['2'])||$object->routeCheck($access)!==true){
			throw new \Exception("路由访问格式错误");
		}*/
	}
	/*
	检测路由各部分是否正确

	@param Array $route 路由访问数组
	@return boole 成功 -true 失败 -false
	*/
	public function routeCheck(Array $route)
	{
		$check=PROJECT.$route['0'];
		//读取路由配置规则
		//

		if(!file_exists($check)){
			throw new \Exception('非法模块');
		}
		$check.=$check.DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.$route['1'].'php';
		if(!file_exists($check)){
			throw new \Exception('非法控制器');
		}

	
		return true;
	}

	/*
		查找模块|控制器|操作
		@param array $route 路由访问数组

	*/
	public function findOperateClass(Array $route)
	{
		//路由检查
		$this->routeCheck($route);
		echo 123;
		//操作类
		$operateclass=DIRECTORY_SEPARATOR.$route['0'].DIRECTORY_SEPARATOR;
		$operateclass.='controller'.DIRECTORY_SEPARATOR.$route['1'].'()';
		
		return new $operateclass;
	}
}