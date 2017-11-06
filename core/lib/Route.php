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
	public static function route()
	{
		$object=new static();
		
		//获取访问请求并拆分
		$access=explode('/',substr($_SERVER['REQUEST_URI'],19));

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
		for($i=0;$i<=2;$i++){
			if(!file_exists(PROJECT.$route[$i])){
				break;
			}
		}
		//读取配置判断路由规则

		//查找出问题的地方
		switch ($i) {
			case '0':
				# code...
				break;
			
			default:
				# code...
				break;
		}

		return true;
	}
}