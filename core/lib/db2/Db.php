<?php
namespace core\lib\db2;


use \core\lib\db2\Assembly;
use \core\lib\db2\Implement;
use \core\lib\db2\DbRelation;

class Db
{

	//查询类对象
	protected $assembly=null;
	
	//执行类对象
	protected $implement=null;

	//映射类对象
	protected $dbrelation=null;

	public function __construct()
	{
		//创建查询类对象
		if(is_null($this->assembly)){
			$this->assembly=new Assembly();
		}

		//创建执行类对象
		if(is_null($this->implement)){
			$this->implement=new Implement();
		}

		//创建映射类对象
		if(is_null($this->dbrelation)){
			$this->dbrelation=new DbRelation();
		}
	}

	/*

		向映射对象中添加字段
		@param string|array $param1 键名|键值对数组
		@param string       $param2 键值            -可选
	*/


}