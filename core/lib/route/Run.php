<?php
namespace core\lib\route;
use core\lib\route\Route;
use core\lib\route\RouteParams;

class Run extends Route
{
	//路由参数对象
	protected $paramobj='';
	//请求参数
	protected $params=[];

	public function __construct()
	{
		$this->paramobj=new RouteParams();
		$this->paramobj->getParam();
	}

	/*
		根据路由执行操作
	*/
	public function execute()
	{
		//获取路由信息
		$this->routeCheck($this->getRouteOperate());
		
		//获取操作对象
		$operateproject=$this->findOperateClass($this->accessoperate);

		//执行路由操作
		$operate=$this->accessoperate['2'];
		$operateproject->$operate();
	}


}