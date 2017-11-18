<?php
namespace core\lib\request;

use \core\lib\request\Request;
use \core\lib\Func;


class RequestParam extends Request
{

	//请求参数信息
	protected $accessparam='';
	
	//请求方式
	protected $method='AUTO';

	//put delete等方式获取的参数
	protected $phpinput='';

	//file
	protected $files='';

	public function __construct()
	{
		
		parent::__construct();

		$this->getRouteOperate();

		$this->accessparam=['GET'=>'','POST'=>'','PUT'=>'','DELETE'=>'','PATCH'=>'','AUTO'=>''];

	}

	/*
		区分请求方式
		$param string $method 请求方式 
	*/
	public function request($method)
	{
		$allowmethod=['GET','POST','PUT','DELETE','PATCH'];

		if(array_search($method,$allowmethod)===false){
			throw new \Exception("非法的请求方法");
		}

		$this->method=$method;

		return $this;
	}

	/*
		获取请求方法
		@return string 请求方法
	*/
	public function method()
	{
		$method=$this->request['REQUEST_METHOD'];

		return $method;
	}

	/*
		获取请求头信息
		@return array 头信息数组
	*/
	public function requestHeader()
	{
		return $this->requestheader;

	}


	/*
		获取请求参数
		@param string|array $paramname 参数名称 可选
		@return string|array 参数值
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
			$this->patch();
			if(!empty($paramname)){
				$this->files();
			}

			return $this->accessparam;
		}

		//选择请求类型
		switch($type){
			case 'GET':return $this->get($paramname);break;
			case 'POST':return $this->post($paramname);break;
			case 'PUT':return $this->put($paramname);break;
			case 'DELETE':return $this->delete($paramname);break;
			case 'FILE':{
				if(empty($paramname)){
					throw new \Exception('非法的文件名称');
				}
				return $this->files($paramname);
				break;
			}
			default:throw new \Exception('非法的请求非法');break;
		}
	}

	/*
		添加请求参数
		@param string $paramname 请求参数名称 必选  
		@param string $paramvalue 请求参数值 可选
	*/
	public function setParam($paramname,$paramvalue,$method='AUTO')
	{

		//判断添加类型
		$method=empty($method)?$this->method:'AUTO';

		//键值方式添加
		
		$this->accessparam[$method][$paramname]=$paramvalue;
		
	}

	/*
		GET获取请求参数
		@param string|array $paramname 参数名称 可选
		@return string|array 参数值
	*/
	public function get($paramname='')
	{

		//参数数组
		$backparam=[];

		//判断路由读取方式
		switch(Func::Config('get_param_pattern')){
			//默认模式
			case '':;
			case '1':$this->accessparam['GET']=$_GET;break;
			// /m 模式
			case '2':{
				$str_accessoperate='/'.implode($this->accessoperate,'/');

				$str_accessuri=str_replace($this->request['SCRIPT_NAME'], '', $this->accessuri);

				$str_accessparam=str_replace($str_accessoperate, '', $str_accessuri);
				$arr_accessparam=explode('/',$str_accessparam);
				$arr_accessparam=array_slice($arr_accessparam,1);
				
				$backparam=[];
				for($i=0;$i<count($arr_accessparam);$i++){
					if($i%2==0&&!empty($arr_accessparam[$i])){
						$backparam[$arr_accessparam[$i]]=$arr_accessparam[++$i];
					}
				}
				$this->accessparam['GET']=$backparam;
			}
			break;

			default:throw new \Exception("非法参数匹配模式");break;
		}

		if(is_string($paramname)&&!empty($paramname)){
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
		@param string|array $paramname 参数名称 可选
		@return string|array 参数值

	*/
	public function post($paramname='')
	{
		//参数数组
		$backparam=[];

		$this->accessparam['POST']=$_POST;

		if(is_string($paramname)&&!empty($paramname)){
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
		@param string|array $paramname 参数名称 可选
		@return string|array 参数值
	*/
	public function put($paramname='')
	{
		return $this->phpinput(empty($paramname)?:$paramname);
	}

	/*
		DELETE获取请求参数
		@param string|array $paramname 参数名称 可选
		@return string|array 参数值
	*/
	public function delete($paraname='')
	{
		return $this->phpinput(empty($paramname)?:$paramname);
	}

	/*
		PATCH获取请求参数
		@param string|array $paramname 参数名称 可选
		@return string|array 参数值
	*/
	public function patch($paramname='')
	{
		return $this->phpinput(empty($paramname)?:$paramname);
	}

	/*
		put\delete等方式获取信息方式
		@param string|array $paramname 参数名称 可选

	*/
	public function phpinput($paramname='')
	{
		$method=$_SERVER['REQUEST_METHOD'];

		//区分操作
		switch($method){
			case 'PUT':{
				$this->phpinput['PUT']=file_get_contents("php://input");
				break;
			}
			case 'DELETE':{
				$this->phpinput['DELETE']=file_get_contents("php://input");
				break;
			}
			case 'PATCH':{
				$this->phpinput['PATCH']=file_get_contents("php://input");
				break;
			}
			default:return '';break;
		}


		if(count(explode('&',$this->phpinput[$method]))==0){
			return '';
		}
		
		foreach(explode('&',$this->phpinput[$method]) as $k=>$v){
			$len=stripos($v,'=');
			$backparam[substr($v,0,$len)]=substr($v,++$len);
		}


		//参数数组
		$backparam2=[];

		$this->accessparam[$method]=$backparam;

		if(is_string($paramname)&&!empty($paramname)){
			return empty($this->accessparam[$method][$paramname])?'':$this->accessparam[$method][$paramname];
		}

		if(is_array($paramname)){
			foreach ($paramname as $k => $v) {
				if(!empty($this->accessparam[$method][$v]))
					$backparam2[$v]=$v;
			}
			return empty($backparam2)?'':$backparam2;
		}

		return $this->accessparam[$method];
		
	}

	/*
		FILE获取请求参数
		@param string $paramname 请求文件名称
		@param string $type 操作执行类型  可选
		@return resource 文件资源

	*/

	public function files($paramname,$type='w+')
	{
		$file=fopen($_FILES[$paramname]['tmp_name'],$type);

		$this->files[$paramname]=$file;

		return $this->files[$paramname];
	}

	/*
		获取添加的参数
		@param string|array $paramname 添加参数名称
		@return string|array 参数值
	*/

	public function auto($paramname='')
	{
		$backparam=[];
			
		if(is_string($paramname)&&!empty($paramname)){
			return empty($this->accessparam['AUTO'][$paramname])?'':$this->accessparam['AUTO'][$paramname];
		}

		if(is_array($paramname)){
			foreach ($paramname as $k => $v) {
				if(!empty($this->accessparam['AUTO'][$v]))
					$backparam[$v]=$v;
			}
			return empty($backparam)?'':$backparam;
		}

		return $this->accessparam['AUTO'];
	}

}