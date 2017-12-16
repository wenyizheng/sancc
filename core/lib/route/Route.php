<?php
namespace core\lib\route;
use \core\lib\BuildConfig;


/*
路由文件
*/

class Route
{

	//请求信息
	protected $request='';

	//请求uri
	protected $accessuri='';

	//请求操作信息
	protected $accessoperate='';




	public function __construct()
	{
		$this->request=$_SERVER;

		$this->accessuri=$_SERVER['REQUEST_URI'];

	}

	/*
		获取路由访问模块|控制器|操作
		@return array 模块|控制器|操作数组

	*/
	public function getRouteOperate()
	{
		//获取访问请求
		$accessuri=str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']);
		
		//拆分
		$reg="#(?<module>((?<=/)\w+(?=/)))/(?<controller>((?<=/)\w+(?=/)))/(?<operate>((?<=/)\w+((?=[/?])|$)))#";
		
		preg_match($reg, $accessuri,$access);
		
		foreach($access as $k=>$v){
			if(!is_numeric($k)){
				$this->accessoperate[$k]=$v;
			}
		}


		return $this->accessoperate;
		
	}
	/*
		检测路由各部分是否正确

		@param Array $route 路由访问数组
		@return boole 成功 -true 失败 -抛出异常
	*/
	public function routeCheck(Array $route)
	{
		
		//读取路由配置规则
		//
		$check=PROJECT.$route['module'];

		if(empty($route['module'])||empty($route['controller'])||empty($route['operate'])){
			throw new \Exception('路由访问错误');
		}

		if(!file_exists($check)){
			throw new \Exception('非法模块');
		}
		$check.=DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.$route['controller'].'.php';
		if(!file_exists($check)){
			
			throw new \Exception('非法控制器');
		}
		
		$operateobject=$this->findOperateClass($route);
		if(!is_object($operateobject)||empty($route['operate'])||!method_exists($operateobject,$route['operate'])){
			
			throw new \Exception('非法操作');
		}
		
		return true;
	}

	/*
		查找模块|控制器|操作
		@param array $route 路由访问数组
		@return object 成功-控制器类实例  失败-抛出异常
	*/
	public function findOperateClass(Array $route)
	{
		
		//操作类
		$operateclass='\\'.$route['module'].'\\';
		$operateclass.='controller'.'\\'.$route['controller'];


		if(!$operateobject=new $operateclass){
			throw new \Exception("非法控制器");
		}
		
		return $operateobject;
	}

}