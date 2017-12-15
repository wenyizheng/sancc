<?php
namespace core\lib;

use \core\lib\config\BuildConfig;
use \core\lib\request\RequestParam;
use \core\lib\Verify;

class Func
{
	//配置对象
	private static $configobj=null;

	//请求对象
	private static $requestobj=null;

	//验证对象
	private static $verifyobj=null;

	//路由对象


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

		if(!isset(self::$requestobj)){
			self::$requestobj=new RequestParam();
		}

		//查询
		if(!empty($paramname)){
			//返回参数
			$backparam=[];

			//所有参数
			$param='';
			
			$param=self::$requestobj->getParam($paramname);

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
			self::$requestobj->setParam($paramname,$paramvalue);
		}


		return self::$requestobj;
	}

	/*
		验证函数
		@param string|array $faild   字段值|字段键值数组
		@param string|array $rule    规则值|规则键值数组
		@param string|array $message 返回信息值|返回信息键值组合
		@return bool|string|object true -真|错误信息 —假  验证类对象

	*/
	public static function Verify($faild='',$rule='',$message='')
	{
		if(!isset(self::$verifyobj)){
			self::$verifyobj=new Verify();
		}

		//调用验证
		if(!empty($faild)&&!empty($rule)){
			if(!empty($message)){
				return self::$verifyobj->check($faild,$rule);
			}else{
				return self::$verifyobj->check($faild,$rule,$message);
			}
		}


		return self::$verifyobj;
	}

}