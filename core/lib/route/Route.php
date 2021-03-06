<?php
namespace core\lib\route;

//use \core\lib\BuildConfig;
use \core\lib\route\RouteRegister;
use \core\lib\route\RouteAnalysis;
use \core\lib\Func;

/*
路由文件
*/

class Route
{

    protected static $instance=null;

	//请求信息
	protected $request='';

	//请求uri
	protected $accessuri='';

	//请求操作信息
	protected $accessoperate='';

	//路由注册对象
    protected $routeregister='';

    //路由解析对象
    protected $routeanalysis='';


    /*
     *
     * 自定义的路由
     *
     */

    //全方法
    public static $allroute=[];

    //GET方法
    public static $getroute=[];

    //POST方法
    public static $postroute=[];

    //delete方法
    public static $deleteroute=[];

    //PATCH方法
    public static $patchroute=[];

    //PUT方法
    public static $putroute=[];




	private function __construct()
	{

	}

	public static function getInstance()
    {
        if(is_null(self::$instance)){
            self::$instance=new self();
        }

        return self::$instance;
    }



	/*
		获取路由访问模块|控制器|操作
		@return array 模块|控制器|操作数组

	*/
	public function getRouteOperate()
	{
		//获取访问请求
		$accessuri=str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']);
        $methodproperty=strtolower($_SERVER['REQUEST_METHOD']).'route';

        $sign=md5($accessuri,true);

        //判断是否是定义的路由
        if(!empty(self::$allroute[$sign])){
            $accessuri=str_replace($_SERVER['SCRIPT_NAME'], '', self::$allroute[$sign]['operate']);
        }elseif(!empty($methodproperty[$sign])){
            $accessuri=str_replace($_SERVER['SCRIPT_NAME'], '', self::${$methodproperty}[$sign]['operate']);
        }


		
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

		if(!file_exists($check.DS.'controller'.DS.$route['controller'].'.php')){
			//查找空控制器
            if(!file_exists($check.DS.'controller'.DS.Func::config('empty_controller').'.php')) {
                throw new \Exception('非法控制器');
            }else{
                $route['controller']=Func::config('empty_controller');
                $this->accessoperate['controller']=Func::config('empty_controller');
            }
		}
		$operateobject=$this->findOperateClass($route);

		if(!is_object($operateobject)||empty($route['operate'])||!method_exists($operateobject,$route['operate'])){
			//查找空操作
		    if(!method_exists($operateobject,Func::Config('empty_operate'))) {
                throw new \Exception('非法操作');
            }else{
		        $route['operate']=Func::Config('empty_operate');
		        $this->accessoperate['operate']=Func::Config('empty_operate');
            }
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