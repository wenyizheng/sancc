<?php
namespace core\lib\config;
/*
处理系统配置
*/

class Config
{
	//config对象
	protected $config='';
	//系统配置
	protected $appconfig=[];
	//数据库配置
	protected $dbconfig=[];

	public function __construct()
	{
		//判断是否存在配置文件
		if(is_file(PROJECT.'config'.DIRECTORY_SEPARATOR.'appconfig.php'))
		{
			$this->appconfig=include PROJECT.'config'.DIRECTORY_SEPARATOR.'appconfig.php';
		}else{
			throw new Exception("无法找到应用配置文件");
		}

	}


	/*
		获取应用配置信息
		@param string $type 配置类型 可选
		@return array 默认-appconfig信息 选择-选择的配置信息
	*/
	public function getConfigInfo($type='')
	{	
		

		switch($type){
			case '':;
			case 'appconfig':return $this->appconfig;break;
			case 'all':{
				//获取数据库配置
				//
				$config=array_merge($this->appconfig,$this->dbconfig);
				return $config;
			};break;
			//其余配置选项
			default:return [];
		}

	}

	/*
		获取特定配置信息
		@param array|string $configname 特定配置名称
		@return array|string 选定的配置信息数组|字符串
	*/
	public function getConfig($configname)
	{
		//返回的配置信息
		$backconfig='';
		$appconfig=$this->getConfigInfo('all');
		//判断是否是字符串
		if(is_string($configname)){

			empty($appconfig[$configname])?'':$backconfig=$appconfig[$configname];
			return $backconfig;
		}
	
		foreach ($configname as $k => $v) {
			if(!empty($appconfig[$v])){
				$backconfig[$v]=$appconfig[$v];
			}else{
				throw new Exception('非法配置信息'.$v);
			}
		}
		return $backconfig;
	}
}