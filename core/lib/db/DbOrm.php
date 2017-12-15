<?php
namespace core\lib\db;

class DbOrm
{
	
	public function __set($key,$value)
	{
		$this->$key=$value;
	}
	
}