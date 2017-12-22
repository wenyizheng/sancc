<?php
namespace test\controller;

use \core\lib\controller\Controller;
use \core\lib\Verify;

use \core\lib\view\View;
use \core\lib\view\ViewRender;

use \core\lib\db2\Db;

use \test\model\Test;

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

		$view=new ViewRender(PROJECT.'test/test1/testview');
		$view->setvar(['c'=>123,'d'=>456,'e'=>['1'=>1,'2'=>2,'3'=>3]]);
		$view->render();


		
	}

	public function test3()
	{
		//var_dump(hash('sha256','abcwwssgg789456'));
		/*$db=new Db();
		$db->where('ab','>','3')->find();*/
		$testmodel=new Test();
		var_dump($testmodel->login());
	}
}