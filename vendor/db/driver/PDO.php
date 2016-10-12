<?php
/**
 * 文件名：PDO.php
 * 文件说明:
 * 时间: 2016/10/12.9:40
 */

namespace vendor\db\driver;
use vendor\db\DB;

class PDO implements DB
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
        $dsn = "mysql:dbname={$configs['dbName']};host={$configs['hostname']}";
        try {
            $conn=new \PDO($dsn,$configs['username'],$configs['password']);
        } catch (\PDOException $e) {
            die($e->getMessage()) ;
        }
        return $conn;
    }

    function close()
    {
        $this->conn=null;
    }

    function query($sql)
    {
        return $this->conn->exec($sql);
    }

}