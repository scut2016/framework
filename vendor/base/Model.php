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

    private $select='';
    private $where='';
    private $group='';
    private $having='';
    private $order='';
    private $limit='';
    public $fields=array();//字段信息
    protected $sql=null;      //sql语句

    function __construct()
    {
        $this->tableName=$this->getTableName();
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

    }
    
    function __set($name,$value)
    {
        if(!in_array($name,$this->getFields()))
            return ;
        $this->data[$name]=$value;
        $table=$this->getTableName();
        $sql="update $table set $name='$value' where {$this->pk}={$this->index}";
        $this->sql=$sql;
        
    }
    function save()
    {
       return  $this->exeDml($this->sql);
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

    public function select($fields='*')
    {

        $fieldsStr=$this->parseFields($fields);
        $this->select=$fieldsStr;
        return $this;
    }

    public function where($where='')
    {
        $whereStr=$this->parseWhere($where);
        $this->where=$whereStr;
        return $this;
    }

    public function parseWhere($where)
    {
        if(empty($where))
            return '';
        $whereStr='';
        if(is_string($where)&&!empty($where))
        {
            $whereStr=$where;
            return ' WHERE '.$whereStr;
        }
        else
        {
            return ' WHERE '.$this->parseArray($where);
        }
    }

    function parseArray($arr)
    {
        $tempArr=[];
        foreach ($arr as $key => $value) {
            $this->addSpecialChar($key);
            if(!is_array($value))
            {
                $tempArr[]=$key.'='.$value;
            }
            else
            {
                $tempArr[]=$key.$value[0].$value[1] ;
            }
        }
        $str=implode(' AND ',$tempArr);
        return $str;
    }
    function group($group='')
    {
        $this->group=$this->parseGroup($group);
        return $this;
    }

    private function parseGroup(&$group)
        // (array('article_id','cat_id'))  `article_id`,`cat_id`
    {
        if(empty($group))
            return '';
        $groupStr='';
        if(is_array($group))
        {
            array_walk($group,array($this, 'addSpecialChar'));
            $groupStr=' GROUP BY '.implode(',',$group);
        }
        elseif(is_string($group)&&!empty($group))
        {
            //article_id,cat_id
            $tempArr=explode(',', $group);
            array_walk($tempArr,array($this, 'addSpecialChar'));
            $groupStr=' GROUP BY '.implode(',',$tempArr);
        }
        return $groupStr;
    }

    public function having($having='')
    {
        $this->having=$this->parseHaving($having);
        return $this;
    }
    private function parseHaving($having)
    {
        $havingStr='';
        if(is_string($having)&&!empty($having))
        {
            $havingStr=$having;
            return ' HAVING '.$havingStr;
        }
        else
        {
            return ' HAVING '.$this->parseArray($having);
        }
    }

    public function order($order='')
    {
        $this->order=$this->parseOrder($order);
        return $this;
    }

    private function parseOrder($order)
    {
        // ' cat_id desc,article_id   asc  '
        //array('cat_id desc','article_id asc');
        if(empty($order))
            return '';
        $orderArr=array();
        if(is_array($order))
        {
            $orderArr=$order;
        }
        elseif(is_string($order)&&!empty($order))
        {
            $orderArr=explode(',', $order);
        }
        $tempArr=[];
        foreach ($orderArr as $key => $value)
        {
            $arr=explode(' ',$value);
            $arr=array_filter($arr);
            $arr=array_values($arr);
            $this->addSpecialChar($arr[0]);
            $end=empty($arr[1])?'':$arr[1];
            $tempArr[]=$arr[0].' '.$end;
        }
        $orderStr=' ORDER BY '.implode(',',$tempArr);
        return $orderStr;
    }

    public function limit($limit='')
    {
        $this->limit=$this->parseLimit($limit);
        return $this;
    }
    //limit 0,10 array(0,10); '0,10'  '10'  array(0);
    private function parseLimit($limit)
    {
        if(empty($limit))
            return '';
        $limitStr='';
        if(is_array($limit))
        {
            if(count($limit)>1)
            {
                $limitStr=' LIMIT '.$limit[0].','.$limit[1];
            }
            else
            {
                $limitStr=' LIMIT '.$limit[0];
            }
        }
        elseif(is_string($limit))
        {
            $limitStr=' LIMIT '.$limit;
        }
        elseif(is_integer($limit)&&$limit>0)
        {
            $limitStr=' LIMIT '.$limit;
        }
        return $limitStr;
    }

    /**
     * [addSpecialChar 字段加上反引号`]
     * @param [type] &$value [字段]
     */
    private  function addSpecialChar(&$value)
    {
        if($value!=="*"&&strpos($value,'.')===false&&strpos($value,'`')===false&&strpos($value,'(')===false)
        {
            $str='`'.trim($value).'`';
            $value=$str;
        }
    }

    private function parseFields(&$fields)
    {

        $fieldsStr='';
        if(is_array($fields))
        {
            array_walk($fields,array($this,'addSpecialChar'));
            $fieldsStr=implode(',',$fields);
        }
        elseif(is_string($fields)&&isset($fields))
        {
            $fieldsArr=explode(',',$fields);
            array_walk($fieldsArr,array($this,'addSpecialChar'));
            $fieldsStr=implode(',',$fieldsArr);
        }
        else
        {
            $fieldsStr=$fields;
        }
        return $fieldsStr;
    }

    public function add($params=array())
    {
        $keysArr=array_keys($params);
        $valuesArr=array_values($params);
        $values=$this->valueProcess($valuesArr);
        $keys=$this->parseFields($keysArr);
        $table=$this->getTableName();
        $this->addSpecialChar($table);
        $sql="INSERT INTO $table ($keys) VALUES ($values)";
        $this->sql=$sql;
        return $this;
    }

    public function set()
    {
        $sql=$this->sql.$this->where.$this->group.$this->having.$this->order.$this->limit;
        $this->clear();
        return $this->exeDml($sql);
    }
    private function clear()
    {
        $this->select=null;
        $this->where=null;
        $this->group=null;
        $this->having=null;
        $this->order=null;
        $this->limit=null;
    }
    public function get()
    {
        $table=$this->getTableName();
        $this->addSpecialChar($table);
        $sql='SELECT '.$this->select.' FROM '.$table.$this->where.$this->group.$this->having.$this->order.$this->limit;
        $this->clear();
        return $this->getAssoc($sql);
    }
    public function delete($condition)
    {
        if(is_array($condition))
        {
            $co=implode(',',$condition);
        }
        else
            $co=$condition;
        $table=$this->getTableName();
        $sql="delete from $table where {$this->pk} in ($co)";
//        dd($sql);
//        die;
       return $this->exeDml($sql);
    }
    private function valueProcess($valuesArr)
    {
        foreach ($valuesArr as $key => $value) {
            if($this->type($value)=='d'||$this->type($value)=='i')
                $valuesArr[$key]=$value;
            else
                $valuesArr[$key]="'".$value."'";
        }
        $valuesStr=implode(',',$valuesArr);
        return $valuesStr;
    }

    private function type($str)
    {
        $type='';
        switch (gettype($str)) {
            case 'string':
                $type='s';
                break;
            case 'double':
                $type='d';
                break;
            case 'integer':
                $type='i';
                break;
            case 'resource':
                $type='b';
                break;
        }
        return $type;
    }
//array('username'=>$user,'password'=>$pwd);
    private function prepareParams($params=array())
    {
        $arr=array();
        if(is_array($params))
        {
            $keys=array_keys($params);
            $fields=$keys;
            $keys=array_map(function($var){
                $this->addSpecialChar($var);
                $var.='=?';
                return $var;
            },$keys);
            $type='';
            foreach ($params as $key => $value) {
                $type.=$this->type($value);
                $arr[]=$params[$key];
            }
            array_unshift($arr, $type);
            array_push($arr,$keys);
            array_push($arr, $fields);
            return $arr;
        }
    }

    public function validate($params=array())
    {
        $table=$this->getTableName();
        $this->addSpecialChar($table);
        $array=$this->prepareParams($params);
        $fields=end($array);
        $fieldsStr=$this->parseFields($fields);
        array_pop($array);
        $where=end($array);
        $whereStr=implode(' AND ',$where);
        $sql="SELECT $fieldsStr FROM $table WHERE $whereStr";
        array_pop($array);

        if($stmt=self::$db->prepare($sql))
        {
            $paramsArr=array();
            foreach ($array as $key => $value) {
                $paramsArr[]=&$array[$key];
            }
            call_user_func_array(array($stmt,'bind_param'), $paramsArr);
            $stmt->execute();
            $result=$stmt->get_result();
            $row=$result->fetch_assoc();
            $stmt->close();
            return $row;
        }
        else
        {
            dd("执行预处理 $sql 语句出现错误");
            die;
        }

    }




}