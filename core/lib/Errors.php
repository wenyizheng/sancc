<?php
namespace core\lib;

/*
	错误处理
*/

class Errors
{

	//错误信息
	protected $errmsg='';
	//错误数组信息
	protected $err=[];

	public function __construct($errno='',$errstr='',$errfile='',$errline='')
	{
		if(!empty($errno)&&!empty($errstr)&&!empty($errfile)&&!empty($errline)){
			$this->err['errno']=$errno;
			$this->err['errstr']=$errstr;
			$this->err['errfile']=$errfile;
			$this->err['errline']=$errline;
			$this->errExc();
		}
	}

	/*
		错误处理
	*/
	protected function errExc()
	{
		//检测判断错误类别

		//编写错误信息样式
		$this->errmsg="错误编号：".$this->err['errno'].'错误信息：'.$this->err['errstr'].'错误文件：'.$this->err['errfile'].'错误行数：'.$this->err['errline'];
		//输出错误信息
		echo $this->errmsg;

		//写入错误日志
		new \core\lib\Logging($this->errmsg);
		die();
	} 
}