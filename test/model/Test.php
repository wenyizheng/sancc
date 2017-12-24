<?php
namespace test\model;

use core\lib\db\Db;

class Test extends Db
{
	public $tablename="test1";

	public function login()
	{
	    //var_dump($this->add());
        //var_dump($this->where('id','=','1')->find());
        /*$this->name='lizheng';
        $this->age=18;
        var_dump($this->add());*/
        //$this->delete([3,4]);
        //$this->id=1;
        //$this->delete();
        //$this->name=123;
        //$this->update(['id'=>6]);
        //var_dump($this->where('id','=','5')->find());
        $this->age=7;
        $this->name='wenyizheng';
        var_dump($this->add());
        echo "<br/>";
        echo "<pre>";
        var_dump($this);
        echo "</pre>";
	}
}