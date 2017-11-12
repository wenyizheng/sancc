<?php
namespace core\lib;

use \core\lib\config\BuildConfig;
use \core\lib\route\RouteParams;


class Func
{
	//配置对象
	private static $configobj=null;

	//路由对象
	private static $paramobj=null;



	/*
		配置函数
		@param string $configname 配置项名称 可选
		@param string $configvalue 配置项值 可选
		@return object BuildConfig对象|string array 配置项值
	*/
	public static function Config($configname='',$configvalue='')
	{

		//参数数组信息
		$configs='';

		if(!isset(self::$configobj)){
			self::$configobj=new BuildConfig();
		}

		//读取配置
		if(!empty($configname)&&empty($configvalue)){
			$configs=self::$configobj->getConfig($configname);
			return $configs;
		}

		//设置信息

		return self::$configobj;
	}

	/*
		参数函数
		@param string|array $paramname 参数名称 可选
		@param string 		$paramvalue 参数值  可选
		@return 
	*/
	public static function Param($paramname='',$paramvalue='')
	{

		if(!isset(self::$paramobj)){
			self::$paramobj=new RouteParams();
		}

		//查询
		if(!empty($paramname)){
			//返回参数
			$backparam=[];

			//所有参数
			$param='';
			
			$param=self::$paramobj->getParam($paramname);

			foreach($param as $k=>$v){
				foreach($v as $k2=>$v2){
					if($paramname==$k2){
						$backparam[$k]=$v2;
					}
				}
			}

			//不同请求类型下是否有参数重名
			return count($backparam)>1?$backparam:implode($backparam);
		}
		
		//设置
		if(!empty($paramname)&&!empty($paramvalue)){
			self::$paramobj->setParam($paramname,$paramvalue);
		}


		return self::$paramobj;
	}
}