<?php
namespace core\lib\route;

use core\lib\route\Route;
/*
 *
 * 路由注册类
 *
 * */
class RouteRegister
{
    protected static $instance=null;



    private function __construct()
    {
    }

    public static function getInstance()
    {
        if(is_null(self::$instance)){
            self::$instance=new self();
        }

        return self::$instance;
    }

    /*
     *
     * 路由注册
     * @param string|array $param1 访问方法|定义路由与实际操作的键值数组
     * @param string       $param2 定义的路由 -可选
     * @param string       $param3 实际的操作 -可选
     * @return bool true|false
     * */
    public function register($param1,$param2='',$param3='')
    {
        /*
         * 路由数组
         * [['operate']=>['method'=>'','operate'=>'','route'=>'']]
         */
        $routelist=[];

        if(!empty($param2)&&!empty($param3)){
            $routelist['method']=$param1;
            $routelist['route']=$param2;
            $routelist['operate']=$param3;
        }else{
            $routelist=$param1;
        }

        foreach($routelist as $k=>$v) {
            $methodproperty=strtolower($v['method']).'route';

            $sign=md5($v['route'],true);

            if(isset(Route::${$methodproperty})){
                Route::${$methodproperty}[$sign]=['route'=>'','operate'=>''];
                Route::${$methodproperty}[$sign]['route']=$v['route'];
                Route::${$methodproperty}[$sign]['operate']=$v['operate'];
            }else{
                throw new \Exception("未知的路由定义");
            }
        }

        return true;
    }

}