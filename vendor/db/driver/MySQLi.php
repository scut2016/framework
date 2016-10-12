<?php
/**
 * 文件名：MySQLi.php
 * 文件说明:
 * 时间: 2016/10/12.9:39
 */

namespace vendor\db\driver;
use vendor\db\DB;
class MySQLi implements DB
{
    private static $instance=null;
    private $conn=null;
    private function __construct($configs)
    {
        $this->conn=$this->connect($configs);
    }

    public static function getInstance($configs=array())
    {
        if(!self::$instance)
            self::$instance=new self($configs);
        return self::$instance;

    }

    function connect($configs = array())
    {
        $conn=new \mysqli($configs['hostname'],$configs['username'],$configs['password'],$configs['dbName']);
        if($conn->connect_error)
            die($conn->connect_error);
        else
            $conn->set_charset($configs['charset']);
        return $conn;
    }

    function close()
    {
       
    }
    
    static function reset()
    {
        self::$instance=null;
    }
    function query($sql)
    {
        return $this->conn->query($sql) ;
    }

     function exeDml($sql)
    {
        $res=$this->conn->query($sql) or die ($this->conn->error);
        $num=$this->conn->affected_rows;
        return $num;
    }
    function __destruct()
    {
        $this->conn->close();
    }

}