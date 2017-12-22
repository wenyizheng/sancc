<?php
namespace core\lib\db2;


use core\lib\Func;

class Implement
{

    //数据库类型
    protected $dbtype='';

    //数据库对象
    protected $dbobj='';

    public function __construct()
    {
        $this->dbtype=Func::Config('dbtype');

        switch($this->dbtype)
        {
            //mysql数据库
            case 'mysql':{
                if(empty($this->dbobj)) {
                   // $this->dbtype=new \core\lib\db2\driver\Mysql();
                }
            }break;

            //...
            default:throw new \Exception("未知的数据库类型");
        }
    }


	/*
		生成不同的驱动并执行其sql组装
	*/
	public function Ganerate($operate,$param)
	{
		if(!empty($operate)){
		    //寻找不同数据库类型下面的不同操作
            call_user_func_array($this->dbobj->$operate,$param);

            //根据返回的结果进行映射

        }else{
		    throw new \Exception("未知的数据库操作");
        }
	}


	/*
	 * 数据库对象映射
	 *
	 * */
	public function objRelation()
    {

    }
}