<?php
namespace core\lib\db;

use \core\lib\Func;

class Db
{

	//数据库对象
	protected $dbobj=null;

	//数据库连接配置
	protected $dbconfig=null;

	public function __construct()
	{
		//加载数据库配置
		if(!isset($this->dbconfig)){
			$this->dbconfig=include(PROJECT.DIRECTORY_SEPARATOR.'dbconfig.php');
			if(!$this->dbconfig){
				throw new \Exception("数据库配置文件错误");
			}
		}

		//数据库对象
		if(!isset($this->dbobj)){
			$dsn=$this->dbconfig['type'].":host:".$this->dbconfig['host'].';dbname='.$this->dbconfig['dbname'].';port='.$this->dbconfig['port'].';charset='.$this->dbconfig['charset'];

			try{
				$this->dbobj=new PDO($dsn,$this->dbconfig['user'],$this->dbconfig['password']);
			}catch(PDOException $e){
				throw new \Exception("数据库连接错误");
			}
		}
	}

	
}