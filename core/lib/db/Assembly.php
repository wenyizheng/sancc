<?php
namespace core\lib\db;

class Assembly
{

    private $driver='';

    private $db='';


	//条件
	private $condition=[
		//条件 ‘字段名’=》【‘field’=>'','relation'=>'','value'=>''】
	    'where'=>'',
        //表
        'table'=>'',
        //插入操作时的子段 '字段名'=》'字段值'
        'field'=>'',
	];

	public function __construct(DbDriver $driver,Db $db)
	{
	    $this->driver=$driver;
	    $this->db=$db;
	    $this->condition['table']=$this->db->tablename;
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
			
			$this->condition['where'][$param1]=['field'=>$param1,'relation'=>$param2,'value'=>$param3];
		}

		//数组方式 ['id'=>['>',1]]
		if(is_array($param1)&&empty($param2)&&empty($param3)){

			foreach($param1 as $k1=>$v1){

				$this->condition['where'][$k1].=['field'=>$k1,'relation'=>$v1['0'],'value'=>$v1['1']];
				
			}
		}

		return $this->db;
	}

	/*
		添加
		@param string|array $param1 键名|关系数组 -可选
		@param string       $param2 键值
	*/
	public function add($param1='',$param2='')
	{
		//获取属性
		$message=$this->db->inserttable;

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


		foreach ($message as $k=>$v){
		    $this->condition['field'][$k]=$v;
        }

        return $this->driver->execute($this->condition);
	}

    /*
		单数据查询
		@param string $primarykey 主键内容  -可选
	    @return array 查询结果

	*/
    public function find($primarykey='')
    {
        if(!empty($primarykey)) {

            if (empty($this->db->primarykey)) {

                $this->where('id', '=', $primarykey);
            } else {
                $this->where($this->db->primarykey, '=', $primarykey);
            }
        }

        $res=$this->driver->query($this->condition);
        $res=['res'=>$res,'condition'=>$this->condition];

        return $res;
    }

    /*
     * 删除
     * @param string|array $primarykey 主建内容  -可选
     * @return bool true|false
     *
     * */
    public function delete($primarykey='')
    {
        //获取用户自设定的逐渐
        $pri=$this->db->primarykey;
        if(empty($pri)){
            $pri='id';
        }

        //属性方式
        if(empty($primarykey)&&!empty($this->db->inserttable[$pri])){
            //var_dump($this->db->inserttable[$pri]);
            $this->where($pri, '=', $this->db->inserttable[$pri]);
        }

        //字符串形式
        if(is_string($primarykey)||is_int($primarykey)){
            $this->where($pri, '=', $primarykey);
        } //数组形式
        elseif(is_array($primarykey)&&count($primarykey)>0){
            foreach ($primarykey as $k=>$v){
                $this->where($pri, '=', $primarykey);
            }
        }

        $res=$this->driver->execute($this->condition);

        return $res;
    }

    /*
     * 更新
     * @param string|array $param1 更新键名|更新键名键值数组    -可选
     * @param string $param2 更新健值   -可选
     * @return bool true|false
     * */
    public function update($param1='',$param2='')
    {
        //主键
        $pri=$this->db->primarykey;
        if(empty($pri)){
            $pri='id';
        }

        //属性获得
        if(!empty($this->db->inserttable)){

            foreach ($this->db->inserttable as $k=>$v){
                //找到主键时设置为查找条件
                if($k==$pri){
                    $this->where($k,'=',$v);
                }else {
                    $this->condition['field'][] = ['field' => $k, 'value' => $v];
                }
            }
        }

        //数组形式
        if(is_array($param1)&&!empty($param1)){

            foreach($param1 as $k=>$v){

                //找到主键时设置为查找条件
                if($k==$pri){
                    empty($v['relation'])?$this->where($k,'=',$v):$this->where($k,$v['relation'],$v['value']);
                }else {
                    $this->condition['field'][] = ['field' => $k, 'value' => $v];
                }
            }
        }

        //字符串形式
        if(!empty($param1)&&!empty($param2)){
            //找到主键时设置为查找条件
            if($param1==$pri){
                $this->where($pri,'=',$param2);
            }else {
                $this->condition['field'][] = ['field' => $k, 'value' => $v];
            }
        }

        //直接主键值
        if((is_string($param1)||is_int($param1))&&empty($param2)){
            $this->where($pri,'=',$param1);
        }

        $res=$this->driver->execute($this->condition);

        return $res;
    }

}