<?php
namespace core\lib\db;


use core\lib\db\driver\Mysql;
use core\lib\Func;

class Db
{

	//条件类对象
	protected $assembly=null;
	
	//执行类对象
	protected $driver=null;

	//映射类对象
	protected $dbrelation=null;

    //需要映射的方法
    public $relationmethods=[
        'find',
    ];

	//数据表信息
    public $tableinfo=[];

    //表名
    public $tablename='';

    //表的数据信息
    public $table=[];

    //表的插入信息
    public $inserttable=[];


	public function __construct()
	{
        //创建驱动类
        if(is_null($this->driver)) {
            $dbtype = Func::Config('dbtype');
            switch ($dbtype) {
                case 'mysql':
                    $this->driver = Mysql::getInstance();
                    break;
                //...
                default:
                    throw new \Exception("未知的数据库类型");
            }
        }

        //创建条件类对象
        if(is_null($this->assembly)){

            $this->assembly=new Assembly($this->driver,$this);
        }

		//创建映射类对象
		if(is_null($this->dbrelation)){

			$this->dbrelation=new DbRelation($this);
		}

        //获取表的基本信息
        $tableinfo=$this->driver->getTable($this->tablename);
        //设置基本信息
        $this->dbrelation->settable($tableinfo);

	}

	/*
	 * 链式操作方法
	 * 向assembly中查找
	 *
	 * */
	public function __call($method,$arguments)
    {
        $returnres='';

        //判断是否是assembly中的方法
        if(method_exists($this->assembly,$method)){
            $returnres=call_user_func_array([$this->assembly,$method],$arguments);

            //判断进行的操作，是否需要映射
            if(in_array($method,$this->relationmethods)){
                return $this->dbrelation->setRelation($method, $returnres);
            }else {
                return $returnres;
            }
        }

    }

    /*
     * 查找数据库
     *
     * */
    public function __get($name)
    {
        // TODO: Implement __get() method.

        if(!empty($this->table[$name])){

            return $this->table[$name];

        }
    }

    /*
     * 插入时设置的属性
     *
     * */
    public function __set($name, $value)
    {
        // TODO: Implement __set() method.

        $this->inserttable[$name]=$value;
    }




}