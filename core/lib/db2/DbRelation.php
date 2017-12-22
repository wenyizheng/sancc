<?php
namespace core\lib\db2;

use core\lib\db2\Db;

class DbRelation
{

    protected $db='';

    //进行映射的数据库对象
    protected $relationdb='';



    public function __construct(Db $db)
    {
        $this->db=$db;

        $this->relationdb=$db;
    }
    /*
     * 设置表信息
     * */
    public function settable($message)
    {
        if(!is_array($message)){
            throw new \Exception("表信息错误");
        }

        foreach ($message as $k1=>$v1){
            $this->db->tableinfo[$v1['Field']]=[
                'Type'=>$v1['Type'],
                'Null'=>$v1['Null'],
                'Key'=>$v1['Key'],
                'Default'=>$v1['Default'],
                'Extra'=>$v1['Extra'],
            ];

            $this->relationdb->tableinfo[$v1['Field']]=[
                'Type'=>$v1['Type'],
                'Null'=>$v1['Null'],
                'Key'=>$v1['Key'],
                'Default'=>$v1['Default'],
                'Extra'=>$v1['Extra'],
            ];
        }

    }

    /*
     *
     * 设置映射对象
     *
     * */
    public function setRelation($method,$returnres)
    {
        switch($method){
            case 'find':{
                foreach ($returnres['res'] as $k=>$v){
                        foreach ($v as $k2 => $v2) {
                            //把查询结果放到对象中
                            $this->relationdb->table[$k2] = $v2;
                        }
                }
                //返回结果
                return $this->relationdb;
            }break;
            /*case 'add':{

            }break;*/
            /*case 'update':{
                var_dump($returnres);
                die();
            }break;*/
            /*case 'delete':{

            }break;*/
        }
    }

}