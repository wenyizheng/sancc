<?php

/*
	配置函数
	@param string $configname 配置项名称 可选
	@param string $configvalue 配置项值 可选
	@return object BuildConfig对象
*/
function Config($configname='',$configvalue='')
{

	//参数数组信息
	$configs='';

	$configobj=new \core\lib\config\BuildConfig();

	//读取配置
	if(!empty($configname)&&empty($configvalue)){
		$configs=$configobj->getConfig($configname);
		return $configs;
	}

	//设置信息

	return $configobj;
}

/*
	参数函数
	@param string $paramname 参数名称
*/
function Param($paramname='')
{

	$paramobj=new \core\lib\route\RouteParams();
	if(!empty($paramname)){
		return $paramobj->getParam($paramname);
	}

}