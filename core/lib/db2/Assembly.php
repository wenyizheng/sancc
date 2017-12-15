<?php
namespace core\lib\db2;

class Assembley
{

	//查询sql语句
	public $selectsql=[
		'select ',
		'field'=>'*',
		' from ',
		'table'=>'',
		'where'=>'',
	];

	//插入sql语句in
	public $insertsql=[
		' insert into ',
		'table'=>'',
		'( ',
		'field'=>'',
		' )',
		'values( ',
		'values'=>'',
		' )',
	];

	//条件
	private $conditions=[
		'where'=>'',
	];

	public function __construct()
	{
		
	}

	/*

		sql信息数组填充
		@param string $type 类型
		@param array  $content 数据 -可选
	*/
	public function sql($type,$content='')
	{
		switch($type){
			//查询操作
			case 'select':{

				$this->selectsql['table']=$this->table;
				//组建sql
				foreach($this->conditions as $k=>$v)
				{
					//判断
					switch($k)
					{
						//where
						case 'where':{
								//为空则跳出
							if(empty($this->conditions['where']))
								continue;
							foreach($this->conditions['where'] as $k=>$v){
								if(!empty($this->selectsql['where'])){
									$this->selectsql['where'].='and '.$v['field'].$v['relation'].$v['value'];
								}else{
									$this->selectsql['where'].=' where '.$v['field'].$v['relation'].$v['value'];
								}
							}
						}break;
						default:continue;
					}
				}
			}break;

			//插入操作
			case 'insert':{
				$this->insertsql['table']=$this->table;
				//设置字段
				$this->insertsql['field']=implode(',',array_keys($content));
				var_dump(implode(',',array_keys($content)));
				//设置内容
				$this->insertsql['values']=implode(',',array_values($content));
			}break;
			default:throw new \Exception("不识别的数据库操作");

		}
		

		

	}

	/*
		单数据查询
		@param string $primarykey 主键内容
	*/
	public function find($primarykey='')
	{

		
		
		$this->sql('select');

		//拼组sql数组为字符串
		$sql='';
		foreach($this->selectsql as $v){
			$sql.=$v;
		}
		$sql.=';';
		

		return $this->query($sql);
	}

	/*
		条件
		@param string|array $param1 键名|关系数组		-可选
		@param string 		$param2 关系数组			-可选
		@param string 		$param3 键值				-可选
	*/
	public function where($param1='',$param2='',$param3='')
	{
		//普通方式
		if(!empty($param1)&&!empty($param2)&&!empty($param3)){
			
			$this->conditions['where'][]=['field'=>$param1,'relation'=>$param2,'value'=>$param3];
		}

		//数组方式 ['id'=>['>',1]]
		if(is_array($param1)&&empty($param2)&&empty($param3)){

			foreach($param1 as $k1=>$v1){

				$this->conditions['where'][].=['field'=>$k1,'relation'=>$v1['0'],'value'=>$v2['1']];
				
			}
		}

		return $this;
	}

	/*
		添加
		@param string|array $param1 键名|关系数组 -可选
		@param string       $param2 键值
	*/
	public function add($param1='',$param2='')
	{
		//获取属性
		$message=get_object_vars($this->orm);

		//普通方式
		if(!empty($param1)&&!empty($param2)){
			$message[$param1]=$param2;
		}

		//数组方式
		if(is_array($param1)&&count($param1)>0){
			foreach($param1 as $k=>$v){
				$message[$k]=$v;
			}
		}
		

		$this->sql('insert',$message);

		//拼组sql数组为字符串
		$sql='';
		foreach($this->insertsql as $v){
			$sql.=$v;
		}
		$sql.=';';

		return $this->execute($sql);
	}

	

}