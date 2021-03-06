<?php
namespace core\lib;

use core\lib\request\Request;
use core\lib\request\RequestParam;

use core\lib\route\Route;


class Run extends Route
{
	//路由参数对象
	protected $paramobj='';
	//请求参数
	protected $params=[];

	public function __construct()
	{
		$this->paramobj=new RequestParam();
	}

	/*
		根据路由执行操作
	*/
	public function execute()
	{

	    if(empty($this->getRouteOperate())){
	        throw new \Exception("路由访问错误");
        }

		//获取路由信息
		$this->routeCheck($this->getRouteOperate());

		//获取操作对象
		$executeproject=$this->findOperateClass($this->accessoperate);

		//执行路由操作
		$execute=$this->accessoperate['operate'];
		$executeproject->$execute();
	}
}