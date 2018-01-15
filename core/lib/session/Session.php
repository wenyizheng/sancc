<?php
namespace core\lib;

use \core\lib\Func;
use core\lib\driver\session\Redis as SRedis;

class Session
{
    public static $instance = null;

    //配置信息
    public static $config = [];

    private function __construct()
    {


        self::$config = Func::Config('session');

        //设置路径
        if (!empty(self::$config['path'])) {
            session_save_path(self::$config['path']);
        }

        //使用redis存储session
        if(self::$config['driver']==='redis'){
            $redis=new SRedis();
            var_dump(session_set_save_handler($redis,true));
        }

    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        session_start();
        return self::$instance;
    }

    /*
     *
     * 检测session是否已经开启等
     *
     * */
    public static function check()
    {
       if(empty(self::$instance)){
           self::getInstance();
       }
    }

    /*
     *
     * 设置session
     * @param string $name session名称
     * @param string $value session内容
     * @return bool true|false
     * */
    public static function set($name,$value)
    {
        self::check();
        $_SESSION[$name]=$value;
        return $_SESSION[$name]=$value;

    }

    /*
     *
     * 获取session
     * @param string $name session名称
     * @return string 对应的session值
     * */
    public static function get($name)
    {
        self::check();

        return $_SESSION[$name];
    }

    /*
     *
     * 检测session存在
     * @param stirng $name session名称
     * @return bool true|false
     * */
    public static function has($name)
    {
        return isset($_SESSION[$name]);
    }

    /*
     *
     * 删除session
     * @param string $name session名称
     * @return bool true|false
     * */
    public static function delete($name)
    {
        unset($_SESSION[$name]);

        return isset($_SESSION[$name])?false:true;
    }

    /*
     *
     * 取值并删除session
     * @param string $name session名称
     * @return string session内容
     * */
    public static function pull($name)
    {
        $value=$_SESSION[$name];

        unset($_SESSION[$name]);

        return $value;
    }




}