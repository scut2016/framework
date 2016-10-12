<?php
/**
 * 文件名：Model.php
 * 文件说明:
 * 时间: 2016/10/12.9:29
 */

namespace vendor\base;

use vendor\core\Factory;
use vendor\core\Iterator;
use vendor\core\Proxy;
use vendor\core\Register;

class Model
{
    protected $tableName='';   //数据表名，不包含前缀
    protected $trueTableName='';//数据表名，包含前缀
    protected $db=null;
    function __construct()
    {
//        $this->db=Factory::getDb();
    }
    public function getModelName()
    {
        $class=get_class($this);
        $len=strlen($class);
        $class=substr($class,0,$len-5);
        $pos=strrpos($class,'\\');
        $class=substr($class,$pos+1);
        return $class;
    }
    private function getTableName()
    {
        if($this->trueTableName)
        {
            return $this->trueTableName;
        }
        else
        {
            return  strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $this->getModelName()));
        }
    }

    function all($type=1)
    {
        $table=$this->getTableName();
        $this->db=Proxy::readDb();
        $sql="select * from $table";
        switch ($type)
        {
            case 1:
                $iterator= new Iterator($this->getAssoc($sql));
                Register::set($table,$iterator);
                return $iterator;
                break;
            default:
                $iterator= new Iterator($this->getRow($sql));
                Register::set($table,$iterator);
                return $iterator;
                break;
        }
    }
    function update($id)
    {
        $this->db=Proxy::writeDb();
        $sql="update student set stu_name='赵敏' where id=$id";
        $this->exeDml($sql);
    }
    function one($id=1)
    {
        $table=$this->getTableName();
        if(Register::get($table))
            $all=Register::get($table);
        else
            $all=$this->all();
        while($all->valid())
        {
            if($all->key()==$id-1)
                break;
            $all->next();
        }
        return $all->current();
    }
    
    private function exeDql($sql)
    {
        $res=$this->db->query($sql) ;
        return $res;
    }

    private function exeDml($sql)
    {
        return $this->db->exeDml($sql);
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