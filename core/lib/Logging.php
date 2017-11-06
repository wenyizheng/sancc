<?php
namespace core\lib;

/*
	日志记录
*/
class Logging
{
	//错误文件名称
	protected $errfilename='';
	//错误文件
	protected $errfile='';
	public function __construct($msg='')
	{
		if(!empty($msg)){
			$this->writeLog($msg);
		}
	}
	/*
		错误信息写入日志
	*/
	public function writeLog($msg)
	{
		$this->errfilename=PROJECT.'log'.DIRECTORY_SEPARATOR.'error'.DIRECTORY_SEPARATOR.date("Ymd",time()).'.log';
		$this->errfile=fopen($this->errfilename,'a');
		fwrite($this->errfile,"#time:".date("Y-m-d H:i:s",time())."\n");
		fwrite($this->errfile, '#'.$msg."\n\n");
		fclose($this->errfile);
	}
}