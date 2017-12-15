<?php
namespace core\lib\db;

use \core\lib\Func;
use \core\lib\db\Assembly;
use \core\lib\db\DbImplement;
use \core\lib\db\DbOrm;
class Db
{

	//数据库对象
	protected $dbobj=null;

	//数据库连接配置
	protected $dbconfig=null;

	//sql拼接对象
	protected $assembly=null;

	//sql执行对象
	protected $implement=null;

	//字段
	protected $orm=null;


	public function __construct()
	{


		if(is_null($this->implement)){
			
			$this->implement=new DbImplement();
		}

		if(is_null($this->orm)){
			$this->orm=new DbOrm();
		}


		if(is_null($this->assembly)){
			$this->assembly=new Assembly();
		}
	}


	public function __set($key,$value)
	{	
		/*if(isset($this->assembly)){
			echo $key;
			foreach(get_object_vars($this) as $k=>$v){
					$this->assembly->$k=$v;
			}
		}*/

		if(is_object($this->orm)){
			$this->orm->$key=$value;
		}
	}

	public function __get($key)
	{

		/*if(!empty($this->field[$key])){
			return $this->field[$key];
		}*/
	}

	public function __call($name,$param)
	{
		
		//检测是否为拼接sql函数
		if(method_exists($this->assembly, $name)){
			$res=call_user_func_array([$this->assembly,$name],$param);
			return $res;
		}
	}

	/*
		
		查询操作执行
		@param string $sql sql语句
		@return object 查询完成后的对象
	*/
	public function query($sql)
	{

		$queryres=$this->implement->query($sql);

    
		$queryarray=[];
		foreach($queryres as $v){
			$queryarray+=$v;
		}
		foreach($queryarray as $k=>$v){
			//为orm添加属性
			$this->orm->$k=$v;
		}
		
		return $this->orm;
	}

	/*
		
		增删改操作执行
		@param string $sql sql语句
		@return object 操作完成后的对象
	*/
	public function execute($sql)
	{
		return $this->implement->execute($sql);
	}
	
}