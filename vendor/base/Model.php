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
    private $data=array();//数据库字段数组
    private $pk='id';
    private $index=0;
    function __construct()
    {
        Register::set('master',Factory::getDb('master'));//获取主库
        Register::set('slave',Factory::getDb('slave'));//获取从库
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
    function update()
    {
       $sql="update student set stu_name='zhagnwuji' where id=1" ;
        $this->exeDml($sql);
       
    }
    
    function __set($name,$value)
    {
        if(!in_array($name,$this->getFields()))
            return ;
        $this->data[$name]=$value;
        $table=$this->getTableName();
        $sql="update $table set $name='$value' where {$this->pk}={$this->index}";
        $this->exeDml($sql);
    }
    function __get($name)
    {
        if(!in_array($name,$this->getFields()))
            return null;
        return $this->one($this->index)[$name];
      
    }
    function one($id=1)
    {
        $this->index=$id;
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
        $db=Proxy::readDb();
        $res=$db->query($sql) ;
        return $res;
    }

    private function exeDml($sql)
    {
        $db=Proxy::writeDb();
        return $db->exeDml($sql);
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
//获取所有字段
    public function getFields()
    {
        $table=$this->getTableName();
        $dbName=Register::get('dbConfig')['dbName'];
        $sql="SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE table_name = '$table' AND table_schema = '$dbName'";
        $arr=$this->getRow($sql);
        $fieldsArr=array();
        foreach ($arr as $key => $value) {
            $fieldsArr[]=$value[0];
        }
//        return array_flip($fieldsArr);
        return $fieldsArr;
    }



}