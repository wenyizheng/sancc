<?php
namespace core\lib;

use core\lib\Func;

class Cookie
{
    private static $config=[];

    public function __construct()
    {
        self::$config=Func::Config('cookie');
    }

    /*
     *
     * 设置cookie
     * @param string $name 名称
     * @param string $value 值
     * @param int    $expire 过期时间 -可选
     * @param string $path  保存路径  -可选
     * @param bool   $secure 安全连接 -可选
     * @return bool  true|false
     * */
    public static function set($name,$value,$expire='',$path='',$secure='')
    {
        empty(self::$config['prefix'])?:$name=$name.self::$config['prefix'];
        empty(self::$config['expire'])?:$expire=self::$config['expire'];
        empty(self::$config['path'])?:$path=self::$config['path'];
        self::$config['secure']==false?:$secure=true;

        if(!empty($expire)){
            return setcookie($name,$value,$expire);
        }elseif(!empty($path)){
            return setcookie($name,$value,$expire,$path);
        }elseif($secure==true){
            return setcookie($name,$value,$expire,$path,$secure);
        }else{
            return setcookie($name,$value);
        }
    }

    /*
     *
     * 检查cookie是否存在
     * @param string $name cookie名称
     * @return bool  true|false
     *
     * */
    public static function has($name)
    {
        return isset($_COOKIE[$name])?true:false;
    }

    /*
     *
     * 删除cookie
     * @param string $name cookie名称
     * @return bool  true|false
     *
     * */
    public static function delete($name)
    {
        return setcookie($name,'',1);
    }

}