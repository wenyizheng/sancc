<?php
namespace test\controller;

use \core\lib\controller\Controller;

class Test1 Extends Controller
{
	public function test1()
	{
		echo "这是测试一方法";
		var_dump($this->Param()->put());
	}

	public function test2()
	{
		echo "这是测试二方法";
	}
}