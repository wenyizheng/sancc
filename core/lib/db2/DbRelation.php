<?php
namespace core\lib\db2;

use \core\lib\db2\DbDriver;


class DbRelation extends DbDriver
{
	
	//表的相关信息
	protected $asstable=[
		'table'=>'',
	];


	public function __set($key,$value){
		$this->key=$value;
	}



	/*

		向映射对象中添加字段
		@param string|array $param1 键名|键值对数组
		@param string       $param2 键值            -可选
	*/
	public function Relationfeild($param1,$param2='')
	{
		//字符串形式
		if(!empty($param1)||!empty($param2)){
			$this->$param1=$param2;
		}

		//数组方式
		if(is_array($param1)){
			
			foreach($param1 as $k=>$v){
				$this->$k=$v;
			}

		}
	}


	/*
		获取表的相关信息
	*/
	public function getTable()
	{

	}

	/*
		设置表的相关信息
		@param string|array $param1 键名|键值对数组
		@param string 		$param2 键值 			-可选
	*/
	public function setTable($param1,$param2='')
	{
		//字符串形式
		if(!empty($param1)&&!empty($param2)){
			$this->asstable[$param1]=$param2;
		}

		//数组方式
		if(is_array($param1)){

			foreach($param1 as $k=>$v){
				$this->asstable[$k]=$v;
			}
		}
	}
}