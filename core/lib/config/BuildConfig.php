<?php
namespace core\lib\config;
use core\lib\config\Config;

/*
	配置相关信息获取
*/
class BuildConfig extends Config
{

	//当前实例化
	protected static $buildconfig='';

	public function __construct()
	{
		parent::__construct();
	}

	/*
		获取系统配置信息
		@param array|string $configname 特定配置名称
		@return array 选定的配置信息数组
	*/
	public static function configs($configname)
	{
		self::$buildconfig=new static();
		return self::$buildconfig->getConfig($configname);
	} 

}