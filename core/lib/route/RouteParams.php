<?php
namespace core\lib\route;
use core\lib\route\Route;

class RouteParams extends Route
{
	
	
	//请求参数信息
	protected $accessparam='';

	public function __construct()
	{
		parent::__construct();

		$this->getRouteOperate();

	}



	/*
		获取路由参数
		@param string|array $paramname 路由参数名称 可选
		@return string|array 路由参数值
	*/
	public function getParam($paramname='')
	{
		//路由参数数组
		$backparam=[];

		$str_accessoperate='/'.implode($this->accessoperate,'/');

		$str_accessuri=str_replace($this->request['SCRIPT_NAME'], '', $this->accessuri);

		$str_accessparam=str_replace($str_accessoperate, '', $str_accessuri);

		
		//判断路由读取方式
		switch(Config('get_param_pattern')){
			//默认模式
			case '':;
			case '1':$this->accessparam=$_GET;break;
			// /m 模式
			case '2':;break;

			default:throw new \Exception("非法参数匹配模式");break;
		}

		if(is_string($paramname)){
			return empty($this->accessparam[$paramname])?'':$this->accessparam[$paramname];
		}

		if(is_array($paramname)){
			foreach ($paramname as $k => $v) {
				if(!empty($this->accessparam[$v]))
					$backparam[$v]=$v;
			}
			return empty($backparam)?'':$backparam;
		}
		
		return $this->accessparam;
		
		
	}
}