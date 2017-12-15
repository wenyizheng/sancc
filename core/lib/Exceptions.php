<?php
namespace core\lib;

/*
	异常处理
*/

class Exceptions extends \Exception
{
	//异常消息
	private $excmsg='';
	//异常对象
	private $exc='';

	public function __construct($e='')
	{
		if(!empty($e)){
			$this->exc=$e;
			//处理异常
			$this->delExc();
		}
	}


	/*
		异常处理
	*/
	public function delExc()
	{

		//报告错误信息
		$this->excmsg='异常错误信息：'.$this->exc->getMessage();
		$this->excmsg.=empty($this->exc->getCode())?'':'异常码：'.$this->exc->getCode();
		$this->excmsg.="<br/>";
		$this->excmsg.='异常文件：'.$this->exc->file;
		$this->excmsg.='异常行号：'.$this->exc->line;
		echo $this->excmsg;

		//写入日志
		new \core\lib\Logging($this->excmsg);
	}

}