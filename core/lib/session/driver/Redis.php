<?php
namespace core\lib\driver\session;

use \core\lib\Exceptions;
use \core\lib\Func;

class Redis implements \SessionHandlerInterface
{
    public $redis=null;
    //session中的redis配置
    public $config=null;
    //session过期时间
    public $timeout=null;
    //session前缀
    public $prefix=null;

    public function __construct()
    {
        $this->config=Func::Config('session')['redis'];
        $this->timeout=Func::Config('session')['timeout'];
        $this->prefix=Func::Config('session')['prefix'];

        $this->redis=new \Redis();
        if(!$this->redis->connect($this->config['host'],$this->config['port'])){
            throw new Exceptions('Redis连接失败');
        }
    }

    /*
     *
     * */
    public function open($save_path,$save_name)
    {
        //检测是否有redis扩展
        if(!extension_loaded('redis')){
            throw new Exceptions('redis扩展不存在');
        }
        return true;
    }

    /*
     *
     * */
    public function close()
    {
        return true;
    }

    /*
     * 删除session
     * @param string $session_id
     * @return bool true|false
     * */
    public function destroy($session_id)
    {
        if($this->redis->del($this->prefix.$session_id)){
            return true;
        }else{
            return false;
        }
    }

    /*
     * 写入
     * @param string $session_id session_id
     * @param string $session_data session_data
     * @return bool true|false
     * */
    public function write($session_id,$session_data)
    {
        if($this->redis->set($this->prefix.$session_id,$session_data,$this->timeout)){
            return true;
        }else{
            return false;
        }
    }

    /*
     * 读取
     * @param string $session_id session_id
     * @return string session_data
     * */
    public function read($session_id)
    {
        if($this->redis->exists($this->prefix.$session_id)){
            return $this->redis->get($this->prefix.$session_id);
        }else{
            return '';
        }
    }

    /*
     *
     * */
    public function gc($maxlifetime)
    {
        return true;
    }

}