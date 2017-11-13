<?php
namespace test\controller;

use \core\lib\controller\Controller;

class Test1 Extends Controller
{
	public function test1()
	{
		echo "这是测试一方法";
		$this->Param()->request('GET')->setParam('999','0617');
		//$this->Param()->setParam('999','0617');
		var_dump($this->Param()->auto());
	}

	public function test2()
	{
		echo "这是测试二方法";
	}
}