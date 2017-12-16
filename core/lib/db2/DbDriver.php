<?php
namespace core\lib\db2;

abstract class DbDriver
{

	
	/*
		获取表的基础信息
		@param string $table 表名
		@return array 信息数组
	*/
	abstract function getTable($table);

	/*
		拼接查询语句
	*/ 
	abstract function getSelect();

	/*
		拼接插入语句
	*/
	abstract function getInsert();

	/*
		拼接删除语句
	*/
	abstract function getDelete();

	/*
		拼接保存语句
	*/
	abstract function getSave();


}