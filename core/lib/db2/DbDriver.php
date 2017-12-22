<?php
namespace core\lib\db2;

abstract class DbDriver
{

	
	/*
		获取表的基础信息
		@param string $table 表名
		@return array 信息数组
	*/
	abstract function getTable($tablename);

	/*
	 * 查询语句
	 * */
	abstract function query($condition);

	/*
	 * 执行语句
	 * */
	abstract function execute($condition);

	/*
	 * 查询拼接
	 * */
	abstract function selectSql($condition);

	/*
	 * 插入拼接
	 * */
	abstract function insertSql($condition);

	/*
	 * 删除拼接
	 * */
	abstract function deleteSql($condition);

	/*
	 * 更新拼接
	 * */
	abstract function updateSql($condition);


}