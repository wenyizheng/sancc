<?php
namespace core\lib\route;
use core\lib\route\Route;
use core\lib\Func;

class RouteParams extends Route
{
	
	
	//请求参数信息
	protected $accessparam='';
	
	//请求方式
	protected $method='';

	public function __construct()
	{
		
		parent::__construct();

		$this->getRouteOperate();

	}

	/*
		区分请求方式
		$param string $method 请求方式 
	*/
	public function request($method)
	{
		$allowmethod=['GET','POST','PUT','DELETE'];

		if(array_search($method,$allowmethod)===false){
			throw new \Exception("非法的请求方法");
		}

		$this->method=$method;
	}

	/*
		获取路由参数
		@param string|array $paramname 路由参数名称 可选
		@return string|array 路由参数值
	*/
	public function getParam($paramname='')
	{

		$type=$this->method;
		//返回所有类型参数
		if(empty($this->method)){
			$this->get();
			$this->post();
			$this->put();
			$this->delete();
			$this->files();

			return $this->accessparam;
		}

		//选择请求类型
		switch($type){
			case 'GET':return $this->get($paramname);break;
			case 'POST':return $this->post($paramname);break;
			case 'PUT':return $this->put($paramname);break;
			case 'DELETE':return $this->delete($paramname);break;
			case 'FILE':return $this->files($paramname);break;
			default:throw new \Exception('非法的请求非法');break;
		}
	}

	/*
		添加请求参数
		@param string|array $paramname 请求参数名称 必选  
		@param string $paramvalue 请求参数值 可选
	*/
	public function setParam($paramname,$paramvalue='')
	{
		//数组方式添加
		if(empty($paramvalue)&&is_array($paramname)){
			foreach($paraname as $k=>$v){
				$this->accessparam[$k]=$v;
			}
		}
		//键值方式添加
		if(!empty($paramvalue)){
			$this->accessparam[$paramname]=$paramvalue;
		}
	}

	/*
		GET获取请求参数
		@param string|array $paramname 路由参数名称 可选
		@return string|array 路由参数值
	*/
	public function get($paramname='')
	{

		//路由参数数组
		$backparam=[];

		$str_accessoperate='/'.implode($this->accessoperate,'/');

		$str_accessuri=str_replace($this->request['SCRIPT_NAME'], '', $this->accessuri);

		$str_accessparam=str_replace($str_accessoperate, '', $str_accessuri);

		
		//判断路由读取方式
		switch(Func::Config('get_param_pattern')){
			//默认模式
			case '':;
			case '1':$this->accessparam['GET']=$_GET;break;
			// /m 模式
			case '2':;break;

			default:throw new \Exception("非法参数匹配模式");break;
		}

		if(is_string($paramname)){
			return empty($this->accessparam['GET'][$paramname])?'':$this->accessparam['GET'][$paramname];
		}

		if(is_array($paramname)){
			foreach ($paramname as $k => $v) {
				if(!empty($this->accessparam['GET'][$v]))
					$backparam[$v]=$v;
			}
			return empty($backparam)?'':$backparam;
		}

		return $this->accessparam['GET'];
	}

	/*
		POST方式获取请求参数

	*/
	public function post($paramname='')
	{
		//路由参数数组
		$backparam=[];

		$this->accessparam['POST']=$_POST;

		if(is_string($paramname)){
			return empty($this->accessparam['POST'][$paramname])?'':$this->accessparam['POST'][$paramname];
		}

		if(is_array($paramname)){
			foreach ($paramname as $k => $v) {
				if(!empty($this->accessparam['POST'][$v]))
					$backparam[$v]=$v;
			}
			return empty($backparam)?'':$backparam;
		}

		return $this->accessparam['POST'];
	}

	/*
		PUT获取请求参数
	*/
	public function put()
	{
		$_PUT = array();   
		if ('put' == $_SERVER['REQUEST_METHOD']) {   
      
			parse_str(file_get_contents('php://input'), $_PUT);  
			var_dump($_PUT);
		}
		echo '123';
		/*var_dump($_SERVER['REQUEST_METHOD']);
		if($_SERVER['REQUEST_METHOD']=='PUT'){
			var_dump($_SERVER);
		}*/
	}

	/*
		DELETE获取请求参数
	*/
	public function delete()
	{

	}

	/*
		FILE获取请求参数
	*/
	public function files()
	{

	}

}