<?php
namespace core\lib;

/*
	验证类

*/
class Verify
{

	//内置规则验证
	protected $rule=[];

	//返回信息
	protected $message=[];


	public function __construct()
	{
		
	}



	/*
		字段验证

		@param string|array $faild   字段值|字段键值数组
		@param string|array $rule    规则值|规则键值数组
		@param string|array $message 返回信息值|返回信息键值组合
		@return bool|string true -真|错误信息 —假 

	*/
	public function check($faild,$rule,$message='')
	{
		//错误返回信息
		$backmessage='';

		//字段验证时
		if(is_string($faild)&&is_string($rule)){
			
			//检测是否为内置验证方法
			if(!empty($this->rule[$rule])){
				$rule=$this->rule[$rule];
			}
			$checkres=$this->checkFaild($faild,$rule);
			//是否正确
			if($checkres){
				return true;
			}else{
				$backmessage=empty($message)?$faild.'验证错误':$faild.$message;
				return $backmessage;
			}
		}

		//数组验证时
		if(is_array($faild)&&is_array($rule)){
			//添加规则
			foreach($rule as $k=>$v){
				$this->rule[$k]=$v;
			}

			//添加返回信息
			if(is_array($message)){
				foreach($messgae as $k=>$v){
					$this->messgae[$k]=$v;
				}
			}

			foreach($faild as $k=>$v){
				if(empty($this->rule[$k])){
					break;
				}
				$rule2=$this->rule[$k];
				$checkres=$this->checkFaild($v,$rule2);

				if($checkres){
					return true;
				}else{
					$backmessage=empty($this->message[$k])?$k.'验证错误':$k.$this->messgae[$k];
					return $backmessage;
				}
			}
		}

	}


	/*
		验证
		@param string $faild 字段值
		@param string $rule  验证规则
		@return bool  true -真|false -假

	*/
	public function checkFaild($faild,$rule)
	{
		$res2=preg_match($rule,$faild,$res);
		
		if($res2===false){
			throw new \Exception("字段验证规则错误");
		}

		foreach($res as $k=>$v){
			if($faild==$v){
				return true;
			}
		}

		return false;
	}


}