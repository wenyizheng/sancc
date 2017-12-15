<?php
namespace test\model;

use core\lib\db\Db;

class Test extends Db
{
	public $table="test1";

	public function login()
	{
		$this->name='wenyizheng';
		$this->id=2;
		$content=$this->where()->find();
		return $content;
	}
}