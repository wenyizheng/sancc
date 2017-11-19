<?php
namespace test\controller;

use \core\lib\controller\Controller;
use \core\lib\Verify;

use \core\lib\view\View;

class Test1 Extends Controller
{
	public function test1()
	{
		echo "这是测试一方法";
		$this->param()->request('GET')->setParam('999','0617');
		//$this->Param()->setParam('999','0617');
		var_dump($this->param()->requestHeader());

		//var_dump(apache_request_headers ());

	}

	public function test2()
	{
		$verify=new Verify();

		$view=new View();
		$view->viewCheck();
		echo "这是测试二方法";
	}
}