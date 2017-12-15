<?php
namespace core\lib\controller;

//快速函数
use \core\lib\Func;
/*
	控制器基类
*/

class Controller
{
	/*
		快速函数
		配置的获取与设置
		@param string|array $configname 配置名称 可选
		@param string       $configvalue 配置值 可选
		@return obj Configparam对象|string array 配置项值
	*/
	public function config($configname='',$configvalue='')
	{
		if(!empty($configname)&&empty($configvalue)){
			return	Func::Config($configname);
		}

		return Func::Config();
	}

	/*
		快速函数
		参数的获取与设置
		@param string  $paramname 参数名称 可选
		@param string  $paramvalue 参数值  可选
		@return obj  RouteParams对象|string 参数值
	*/
	public function param($paramname='',$paramvalue='')
	{
		if(!empty($paramname)&&empty($paramvalue)){
			return Func::Param($paramname);
		}

		if(!empty($paramname)&&!empty($paramvalue)){
			Func::Param($paramname,$paramvalue);
		}

		return Func::Param();
	}

	/*
		验证函数
	*/
	public function verify($faild='',$rule='',$message='')
	{
		return Func::Verify($faild,$rule,$message);
	}
}