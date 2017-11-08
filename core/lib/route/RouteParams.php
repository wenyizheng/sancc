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
	*/
	public function getParam()
	{
		$str_accessoperate='/'.implode($this->accessoperate,'/');

		$str_accessuri=str_replace($this->request['SCRIPT_NAME'], '', $this->accessuri);

		$str_accessparam=str_replace($str_accessoperate, '', $str_accessuri);

		//默认正则匹配模式
		//$paramreg1="#\/\w*\/?#";
		//preg_match_all($paramreg1,$str_accessparam,$str_accessparam);
		//var_dump($str_accessparam);
		//var_dump($_SERVER);
		
	}
}