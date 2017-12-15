<?php
namespace core\lib\db;

use \core\lib\db\Db;
use \core\lib\Func;

class DbImplement extends Db
{
	//dsn
	protected $dsn=null;
	//数据库用户
	protected $user=null;
	//数据库密码
	protected $password=null;

	//数据库连接对象
	protected $connect=null;

	public function __construct()
	{
		$type=Func::config('type');
		$host=Func::config('host');
		$dbname=Func::config('dbname');
		$this->user=Func::config('user');
		$this->password=Func::config('password');
		$port=Func::config('port');
		$charset=Func::config('charset');
		$this->dsn="{$type}:host={$host};dbname={$dbname};charset={$charset}";

	}


	/*
		
		建立数据库连接

	*/
	public function connect()
	{
		if(!isset($this->connect)){
			try{
				$this->connect=new \PDO($this->dsn,$this->user,$this->password);
			}catch(PDOException $e){
				throw new \Exception("数据库错误".$e->getMessage());
			}
		}
	}


	/*
		
		查询操作
		@param string $sql 查询语句
		@return array 查询结果
	*/
	public function query($sql)
	{	var_dump($sql);

			$this->connect();

			$queryres=$this->connect->query($sql);
			$queryres->setFetchMode(\PDO::FETCH_ASSOC);
			return $queryres;
	}

	/*
		
		增删改操作
		@param string $sql 执行语句
		@return array 执行结果
	*/
	public function execute($sql)
	{
		$this->connect();
		var_dump($sql);
		$executeres=$this->connect->exec($sql);
		
		return $executeres;
	}

	

}