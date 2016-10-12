<?php
/**
 * 文件名：Model.php
 * 文件说明:
 * 时间: 2016/10/12.9:29
 */

namespace vendor\base;

use vendor\core\Factory;

class Model
{
    protected $db=null;
    function __construct()
    {
        $this->db=Factory::getDb();
    }

    function all($type=1)
    {
        $sql='select * from student';
        switch ($type)
        {
            case 1:
                return $this->getAssoc($sql);
                break;
            default:
                return $this->getRow($sql);
                break;
        }
    }

    private function exeDql($sql)
    {
        $res=$this->db->query($sql) or die ("执行 $sql 错误".$this->db->error);
        return $res;
    }
    private  function exeDml($sql)
    {
        $res=$this->db->query($sql) or die ($this->db->error);
        $num=$this->db->affected_rows;
        return $num;
    }
    private  function getAssoc($sql)
    {
        $arr=array();
        $res=$this->exeDql($sql);
        while($row=$res->fetch_assoc())
        {
            $arr[]=$row;
        }
        $res->free();
        return $arr;
    }
    private  function getRow($sql)
    {
        $arr=array();
        $res=$this->exeDql($sql);
        while($row=$res->fetch_row())
        {
            $arr[]=$row;
        }
        $res->free();
        return $arr;
    }
}