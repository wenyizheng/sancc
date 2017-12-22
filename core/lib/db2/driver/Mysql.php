<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17-12-15
 * Time: 下午9:05
 */
namespace core\lib\db2\driver;

use \core\lib\db2\DbDriver;
use core\lib\Func;

class Mysql extends DbDriver
{
    private  $pdo=null;

    private static $instance=null;

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
        'value( ',
        'value'=>'',
        ' )',
        'where'=>'',
    ];

    //删除sql语句
    public $deletesql=[
        'delete from ',
        'table'=>'',
        ' where ',
        'where'=>'',
    ];

    //更新sql语句
    public $updatesql=[
        'update ',
        'table'=>'',
        ' set ',
        'field'=>'',
        ' where ',
        'where'=>'',
    ];

    private function __construct()
    {

        //创建数据库连接
        $type = Func::Config('dbtype');//数据库类型
        $host = Func::Config('host');//地质
        $port=Func::Config('port');//端口
        $dbname = Func::Config('dbname');//数据库名称
        $user = Func::Config('user');//用户
        $pwd = Func::Config('password');//密码

        $dsn = $type.":host=".$host.";port=".$port.";dbname=".$dbname;

        try {
            $this->pdo = new \PDO($dsn, $user, $pwd);
        } catch (\PDOException $e) {
            throw new \Exception("PDO连接错误  ".$e->getMessage());
        }

    }

    /*
     *
     * 获得实例对象
     *
     * */
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new Mysql();
        }

        return self::$instance;
    }


    /*
     *
     * 查找sql信息数组填充
     *
     * */
    public function selectSql($condition)
    {
        $this->selectsql['table']=$condition['table'];

        //组建sql
        foreach($condition as $k=>$v)
        {

            //判断
            switch($k)
            {
                //where
                case 'where':{
                    //为空则跳出
                    if(empty($condition['where']))
                        continue;
                    foreach($condition['where'] as $k2=>$v2){
                        if(!empty($this->selectsql['where'])){
                            $this->selectsql['where'].='and '.$k2.$v2['relation'].$k2;
                        }else{
                            $this->selectsql['where'].=' where '.$k2.$v2['relation'].':'.$k2;
                        }
                    }
                }break;
                default:continue;
            }

        }
    }


    /*
     *
     * 插入sql信息数组填充
     *
     * */
    public function insertSql($condition)
    {
        $this->insertsql['table']=$condition['table'];

        foreach ($condition as $k=>$v){

            switch($k){
                case 'where':{
                    //为空则跳出
                    if(empty($condition['where']))
                        continue;
                    foreach($condition['where'] as $k2=>$v2){
                        if(!empty($this->selectsql['where'])){
                            $this->selectsql['where'].='and '.$k2.$v2['relation'].$k2;
                        }else{
                            $this->selectsql['where'].=' where '.$k2.$v2['relation'].':'.$k2;
                        }
                    }
                }break;
                case 'field':{
                    //为空则跳出
                    if(empty($condition['field']))
                        continue;
                    foreach ($condition['field'] as $k2=>$v2){
                        if(!empty($this->insertsql['field'])&&!empty($this->insertsql['value'])){
                            $this->insertsql['field'].=",{$k2}";
                            $this->insertsql['value'].=",:{$k2}";
                        }else{
                            $this->insertsql['field'].="{$k2}";
                            $this->insertsql['value'].=":{$k2}";
                        }
                    }
                }break;
            }
        }
    }


    /*
     *
     * 删除sql信息数组填充
     *
     * */
    public function deleteSql($condition)
    {
        $this->deletesql['table']=$condition['table'];

        //删除字段数组
        $key=[];

        foreach($condition['where'] as $k=>$v){
            $key[$k]['0']=$v['value'];
        }


        $i=0;
        foreach($key as $k=>$v){
            if(empty($this->deletesql['where'])){
                $this->deletesql['where'].=$k." in (";
                foreach ($v as $v2){

                    $this->deletesql['where'].=":$k".$i.',';
                    $i++;
                }
                $i=0;
                //取出末端逗号
                $this->deletesql['where']=rtrim($this->deletesql['where'],',');

                $this->deletesql['where'].=')';
            }else{
                $this->deletesql['where'].=' and '.$k." in (";
                foreach ($v as $v2){
                    $this->deletesql['where'].=":$k".$i.',';
                    $i++;
                }
                $i=0;

                //取出末端逗号
                $this->deletesql['where']=rtrim($this->deletesql['where'],',');
                $this->deletesql.=')';
            }
        }
    }

    /*
     *
     * 更新sql信息数组填充
     *
     * */
    public function updateSql($condition)
    {

        $this->updatesql['table']=$condition['table'];

        foreach ($condition as $k=>$v){

            switch($k){
                case 'where':{
                    //为空则跳出
                    if(empty($condition['where']))
                        continue;

                    foreach ($condition['where'] as $k2=>$v2){
                        if(empty($this->updatesql['where'])){
                            $this->updatesql['where'].="{$v2['field']}{$v2['relation']}:{$v2['field']}";
                        }else{
                            $this->updatesql['where'].=" and {$v2['field']}{$v2['relation']}:{$v2['field']}";
                        }
                    }
                }break;
                case 'field':{
                    //为空则跳出
                    if(empty($condition['field']))
                        continue;

                    foreach($condition['field'] as $k2=>$v2){

                        $this->updatesql['field'].=" {$v2['field']}=:{$v2['field']},";
                    }

                    //取出右端逗号
                    $this->updatesql['field']=rtrim($this->updatesql['field'],',');
                }break;
               // case ''

            }
        }
    }


    /*
     *
     * 获取表信息
     *
     * */
    public function getTable($tablename)
    {
        // TODO: Implement getTable() method.
        $sql="desc ".$tablename.';';

        $stmt=$this->pdo->prepare($sql);
        $stmt->execute();
        $table_fields=$stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $table_fields;
    }


    /*
     *
     * 查询操作
     *
     * */
    public  function query($condition)
    {
        $this->selectSql($condition);
        $sql=implode($this->selectsql);
        $stmt=$this->pdo->prepare($sql);

        //绑定参数
        if(!empty($condition['where'])) {
            foreach($condition['where'] as $k=>$v) {
                $stmt->bindParam(":{$k}",$v['value']);
            }
        }

        if($stmt->execute()) {

            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            //还原查询sql
            $this->selectsql=[
                'select ',
                'field'=>'*',
                ' from ',
                'table'=>'',
                'where'=>'',
            ];

            return $res;
        }else{
            return false;
        }
    }


    /*
     *
     * 增删操作
     *
     * */
    public function execute($condition)
    {
        $sql='';

        //获取回朔信息
        $backtrace = debug_backtrace();
        array_shift($backtrace);

        //获得调用者方法名
        $transferfunc=$backtrace['0']['function'];



        //根据不同调用方法执行不同sql拼装
        switch($transferfunc)
        {
            //添加
            case 'add':{
                $this->insertSql($condition);

                $stmt=$this->pdo->prepare(implode($this->insertsql));

                //参数绑定
                foreach ($condition as $k=>$v){

                    switch($k)
                    {
                        case 'where':{
                            if(empty($condition['where']))
                                continue;
                            if(empty($this->insertsql['where']))
                                continue;

                            foreach($condition['where'] as $k2=>$v2) {
                                $stmt->bindParam(":{$k2}",$v2['value']);
                            }
                        }continue;

                        case 'field':{
                            if(empty($condition['field']))
                                continue;
                            if(empty($this->insertsql['field']))
                                continue;

                            foreach($condition['field'] as $k2=>$v2){
                                $stmt->bindParam(":".$k2,$condition['field'][$k2]);
                            }
                        }continue;
                    }
                }

                $stmt->execute();

                $row=$stmt->rowCount();



                if($row>0){
                    //还原插入数组
                    $this->insertsql=[
                        ' insert into ',
                        'table'=>'',
                        '( ',
                        'field'=>'',
                        ' )',
                        'value( ',
                        'value'=>'',
                        ' )',
                        'where'=>'',
                    ];
                }

                return $row;

            }break;
            //删除
            case 'delete':{
                $this->deleteSql($condition);

                $stmt=$this->pdo->prepare(implode($this->deletesql));

                //删除字段数组
                $key=[];

                foreach($condition['where'] as $k=>$v){
                    $key[$k]['0']=$v['value'];
                }


                foreach($key as $k=>$v){
                    foreach($v as $k2=>$v2) {
                        $stmt->bindParam(':'.$k.$k2,$v[$k2]);
                    }
                }

                $stmt->execute();

                $row=$stmt->rowCount();

                return $row;
            }break;
            //修改
            case 'update':{

                $this->updateSql($condition);

                $stmt=$this->pdo->prepare(implode($this->updatesql));

                //修改字段参数绑定
                foreach($condition['field'] as $k=>$v){
                    $stmt->bindParam(':'.$v['field'],$v['value']);
                }


                //条件字段绑定
                foreach($condition['where'] as $k=>$v){
                    $stmt->bindParam(':'.$k,$v['value']);
                }

                $stmt->execute();
                $row=$stmt->rowCount();

                return $row;
            }
            //...
            default:throw new \ErrorException('未知的sql方法'.$transferfunc);
        }

    }
}